<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImageStoreService
{
    public function __construct(
        protected string $disk = 's3'
    ) {}

    /**
     * Upload an image to the configured disk (default S3) and return its data.
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function uploadImage(Request $request): array
    {

        $name = Str::uuid();
        $path = $request->file('image')->store(
            'public/upload/files/' . Auth::user()->company_id . '/' . $request->directory,
            's3'
        );

        if (!$path) {
            throw new \Exception('Failed to upload image.');
        }

        $data = [
            'image_name' => $name,
            'image_path' => $path,
        ];

        return $data;
    }


    /**
     * Delete a file from storage.
     */
    public function delete(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }
}
