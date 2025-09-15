<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Services\ErrorLog\ErrorLogService;

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

            // Resize raster images (JPEG/PNG/etc.) to width 512px keeping aspect ratio without upscaling
            $manager = new ImageManager(new Driver());
            $image = $manager->read($uploadedFile->getPathname());
            $image->scaleDown(width: 512);

            // Re-encode based on original type (default to JPEG)
            $usePng = $extension === 'png' || $mimeType === 'image/png';
            if ($usePng) {
                $encoded = $image->toPng();
                $filename = $name . '.png';
            } else {
                $encoded = $image->toJpeg(quality: 85);
                $filename = $name . '.jpg';
            }

            // Store processed image on configured disk
            $put = Storage::disk($this->disk)->put($directory . '/' . $filename, $encoded->toString());
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
