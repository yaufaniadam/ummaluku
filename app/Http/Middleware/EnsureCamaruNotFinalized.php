<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCamaruNotFinalized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Jika user adalah Mahasiswa, cek apakah mereka punya aplikasi yang sudah finalisasi
        if ($user && $user->hasRole('Mahasiswa')) {
            $application = \App\Models\Application::whereHas('prospective', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->first();
            
            // Jika ada aplikasi dan statusnya sudah_registrasi, blokir akses ke /camaru
            if ($application && $application->status === 'sudah_registrasi') {
                return redirect()->route('mahasiswa.dashboard')
                    ->with('error', 'Anda sudah terdaftar sebagai mahasiswa. Silakan gunakan dashboard mahasiswa.');
            }
        }
        
        return $next($request);
    }
}
