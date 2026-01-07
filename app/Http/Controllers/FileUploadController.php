<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FileUploadController extends Controller
{
    /**
     * Handle file upload for courses/episodes.
     */
    public function upload(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'file' => 'required|file|max:102400', // 100MB max
                'type' => 'required|string|in:video,image',
            ]);

            $file = $request->file('file');
            $type = $validated['type'];
            
            // Validate file type based on type parameter
            if ($type === 'video') {
                // Check if file is a valid video type
                $videoMimes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/webm'];
                $fileMime = $file->getMimeType();
                if (!in_array($fileMime, $videoMimes) && !in_array($file->getClientOriginalExtension(), ['mp4', 'mov', 'avi', 'wmv', 'webm', 'mkv', 'flv'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid video format. Allowed: MP4, MOV, AVI, WMV, WebM, MKV, FLV'
                    ], 422);
                }
            } elseif ($type === 'image') {
                if (!$file->isValid() || !in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid image format. Allowed: JPG, PNG, GIF, WebP'
                    ], 422);
                }
            }
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store file based on type
            $path = $file->storeAs(
                $type . 's/' . date('Y/m'),
                $filename,
                'public'
            );

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store file. Please check server permissions.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'filename' => $filename,
                'size' => $file->getSize(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', array_merge(...array_values($e->errors())))
            ], 422);
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle profile photo upload.
     */
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // 5MB max for images
        ]);

        $file = $request->file('photo');
        
        // Generate unique filename
        $filename = 'profile_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Resize and store
        $path = $file->storeAs(
            'profile-photos',
            $filename,
            'public'
        );

        // Update user profile photo
        auth()->user()->update([
            'profile_photo_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Delete a file.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->path;
        
        // Delete file from storage
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
            
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File not found',
        ], 404);
    }
}

