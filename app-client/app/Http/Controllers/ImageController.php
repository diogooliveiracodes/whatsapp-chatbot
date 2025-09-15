<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Storage\ImageStoreService;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreImageRequest;

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

    public function upload(StoreImageRequest $request)
    {
        try {
            // Early detection of low-level PHP upload errors
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!$file->isValid()) {
                    return response()->json([
                        'message' => $file->getErrorMessage(),
                        'errors' => [
                            'image' => [$file->getErrorMessage()],
                        ],
                    ], 422);
                }
            }

            $imageData = $this->imageStoreService->uploadImage($request);

            return response()->json([
                'image_name' => $imageData['image_name'],
                'image_path' => $imageData['image_path'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'upload',
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'message' => $e->getMessage() ?: 'Error uploading image',
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
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'delete',
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Error deleting image',
            ], 500);
        }
    }
}
