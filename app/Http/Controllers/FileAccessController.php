<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FileAccessController extends Controller
{
    /**
     * Serve a secure file from private storage.
     */
    public function serve(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            abort(404);
        }

        // 1. Basic check: Ensure path is within allowed directories to prevent path traversal
        if (!str_starts_with($path, 'documents/') && !str_starts_with($path, 'payment_proofs/')) {
            abort(403, 'Unauthorized path.');
        }

        // 2. Authorization Logic
        $user = Auth::user();

        // Allow Super Admin and relevant staff roles to see everything
        if ($user->hasRole(['Super Admin', 'Admin', 'Staf Admisi', 'Staf Keuangan'])) {
            return $this->serveFile($path);
        }

        // Ownership check for Mahasiswa/Camaru
        // We assume the path structure is 'documents/{application_id}/filename' 
        // or 'payment_proofs/.../{application_id}/filename'
        
        $pathParts = explode('/', $path);
        
        // For documents/{application_id}/...
        if (str_starts_with($path, 'documents/')) {
            $applicationId = $pathParts[1] ?? null;
            if ($applicationId) {
                // Check if the application belongs to the logged in user
                $ownsApplication = \App\Models\Application::where('id', $applicationId)
                    ->whereHas('prospective', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->exists();

                if ($ownsApplication) {
                    return $this->serveFile($path);
                }
            }
        }

        // If no authorization matches
        abort(403, 'Anda tidak memiliki izin untuk mengakses file ini.');
    }

    /**
     * Helper to serve the file from the private disk.
     */
    private function serveFile($path)
    {
        // Try the private backup first, then fallback to public (during transition)
        if (Storage::disk('local')->exists($path)) {
            return response()->file(storage_path('app/private/' . $path));
        }

        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }

        abort(404, 'File tidak ditemukan.');
    }
}
