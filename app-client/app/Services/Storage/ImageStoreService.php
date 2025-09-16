<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Support\Facades\Log;

class ImageStoreService
{
    protected string $disk;

    public function __construct(
        protected ErrorLogService $errorLogService
    ) {
        $this->disk = (string) (config('filesystems.default') ?? 's3');
    }

    /**
     * Upload an image to the configured disk (default S3) and return its data.
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function uploadImage(Request $request): array
    {
        try {

            $uploadedFile = $request->file('image');
            $name = (string) Str::uuid();

            $companyId = Auth::user()->company_id;
            $directory = 'public/upload/files/' . $companyId . '/' . $request->directory;

            $mimeType = $uploadedFile->getMimeType();
            $extension = strtolower($uploadedFile->getClientOriginalExtension());

            // Skip resizing for SVG and GIF to preserve vector/animation
            $isSvg = $extension === 'svg' || $mimeType === 'image/svg+xml';
            $isGif = $extension === 'gif' || $mimeType === 'image/gif';

            if ($isSvg || $isGif) {
                $filename = $name . '.' . ($extension ?: 'bin');
                $stored = Storage::disk($this->disk)->putFileAs($directory, $uploadedFile, $filename);

                if (!$stored) {
                    throw new \Exception('Failed to upload image.');
                }

                return [
                    'image_name' => $name,
                    'image_path' => $directory . '/' . $filename,
                ];
            }

            // Process raster images with native libraries (prefer Imagick)
            $sourcePath = $uploadedFile->getPathname();
            $binary = null;
            $filename = null;

            if (extension_loaded('imagick') && class_exists('\\Imagick')) {
                $imagickClass = '\\Imagick';
                $img = new $imagickClass();
                $img->readImage($sourcePath);

                $img->autoOrientImage();

                $w = $img->getImageWidth();
                if ($w > 512) {
                    $img->thumbnailImage(512, 512, true, true);
                }
                $img->stripImage();

                $usePng = $extension === 'png' || $mimeType === 'image/png';
                if ($usePng) {
                    $img->setImageFormat('png');
                    $filename = $name . '.png';
                } else {
                    $img->setImageFormat('jpeg');
                    $img->setImageCompressionQuality(85);
                    $filename = $name . '.jpg';
                }

                $binary = $img->getImagesBlob();
                $img->clear();
                $img->destroy();
            } else {
                // Fallback: GD with EXIF orientation handling
                $usePng = $extension === 'png' || $mimeType === 'image/png';

                if (!$usePng) {
                    $src = @imagecreatefromjpeg($sourcePath);
                } else {
                    $src = @imagecreatefrompng($sourcePath);
                }
                if (!$src) {
                    throw new \Exception('Failed to decode image.');
                }

                $srcWidth = imagesx($src);
                $srcHeight = imagesy($src);
                $exifOrientation = null;

                if (!$usePng && function_exists('exif_read_data')) {
                    $exif = @exif_read_data($sourcePath);
                    $exifOrientation = $exif['Orientation'] ?? null;
                    if (!empty($exifOrientation)) {
                        $orientation = (int) $exifOrientation;
                        switch ($orientation) {
                            case 3:
                                $src = imagerotate($src, 180, 0);
                                break;
                            case 6:
                                $src = imagerotate($src, -90, 0);
                                $tmp = $srcWidth; $srcWidth = $srcHeight; $srcHeight = $tmp;
                                break;
                            case 8:
                                $src = imagerotate($src, 90, 0);
                                $tmp = $srcWidth; $srcWidth = $srcHeight; $srcHeight = $tmp;
                                break;
                        }
                    }
                }


                // Fallback: Apenas rotaciona se for JPEG sem EXIF e estiver em landscape
                // (assumindo que fotos landscape sem EXIF são portraits mal orientadas)
                if (!$usePng && empty($exifOrientation) && $srcWidth > $srcHeight) {
                    $src = imagerotate($src, -90, 0);
                    $tmp = $srcWidth; $srcWidth = $srcHeight; $srcHeight = $tmp;
                }

                $target = $src;
                if ($srcWidth > 512) {
                    $newWidth = 512;
                    $newHeight = (int) round(($srcHeight * $newWidth) / max(1, $srcWidth));

                    $target = imagecreatetruecolor($newWidth, $newHeight);
                    if ($usePng) {
                        imagealphablending($target, false);
                        imagesavealpha($target, true);
                        $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
                        imagefill($target, 0, 0, $transparent);
                    }
                    imagecopyresampled($target, $src, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
                }

                ob_start();
                if ($usePng) {
                    imagepng($target);
                    $filename = $name . '.png';
                } else {
                    imagejpeg($target, null, 85);
                    $filename = $name . '.jpg';
                }
                $binary = ob_get_clean();

                if (is_object($src) || (function_exists('is_resource') && is_resource($src))) { imagedestroy($src); }
                if (isset($target) && $target !== $src && (is_object($target) || (function_exists('is_resource') && is_resource($target)))) { imagedestroy($target); }
            }

            // Store processed image on configured disk
            $path = $directory . '/' . $filename;
            $put = Storage::disk($this->disk)->put($path, $binary);
            if (!$put) {
                throw new \Exception('Failed to upload image.');
            }


            return [
                'image_name' => $name,
                'image_path' => $directory . '/' . $filename,
            ];
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'uploadImage', 'request_data' => $request->all()]);

            throw new \Exception('Failed to upload image.');
        }
    }


    /**
     * Delete a file from storage.
     */
    public function delete(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }
}
