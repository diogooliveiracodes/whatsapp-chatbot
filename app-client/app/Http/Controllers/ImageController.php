<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Storage\ImageStoreService;
use App\Services\ErrorLog\ErrorLogService;

class ImageController extends Controller
{

    /**
     * ImageController constructor.
     *
     * @param ErrorLogService $errorLogService
     * @param ImageStoreService $imageStoreService
     */
    public function __construct(
        protected ErrorLogService $errorLogService,
        protected ImageStoreService $imageStoreService
    ) {}

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'directory' => 'required|string',
            ]);

            $imageData = $this->imageStoreService->uploadImage($request);

            return response()->json([
                'image_name' => $imageData['image_name'],
                'image_path' => $imageData['image_path'],
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'upload',
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Error uploading image',
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'image_path' => 'required|string',
            ]);

            $imagePath = $request->input('image_path');
            $imagePath = $this->imageStoreService->delete($imagePath);

            if (!$imagePath) {
                $this->errorLogService->logError(new \Exception('Image not found'), [
                    'action' => 'delete',
                    'request_data' => $request->all(),
                ]);
                return response()->json([
                    'message' => 'Image not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'delete',
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting image',
            ], 500);
        }
    }
}
