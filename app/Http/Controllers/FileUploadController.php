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
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'type' => 'required|string|in:video,image',
        ]);

        $file = $request->file('file');
        $type = $request->type;
        
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store file based on type
        $path = $file->storeAs(
            $type . 's/' . date('Y/m'),
            $filename,
            'public'
        );

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
            'filename' => $filename,
        ]);
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

