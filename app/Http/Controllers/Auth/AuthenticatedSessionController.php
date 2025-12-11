<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        $user = $request->user();

        // Cek peran pengguna dan arahkan ke dashboard yang sesuai
        if ($user->hasRole(['Super Admin'])) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole(['Direktur Admisi', 'Staf Admisi'])) {
            return redirect()->route('admin.pmb.dashboard');
        }

        if ($user->hasRole(['Direktur SDM', 'Staf SDM'])) {
            return redirect()->route('admin.sdm.dashboard');
        }

        if ($user->hasRole(['Direktur Keuangan', 'Staf Keuangan'])) {
            return redirect()->route('admin.keuangan.dashboard');
        }
        if ($user->hasRole(['Dosen'])) {
            return redirect()->route('dosen.dashboard');
        }

        if ($user->hasRole('Camaru')) {
            return redirect()->route('pendaftar');
        }
        if ($user->hasRole('Mahasiswa')) {
            return redirect()->route('mahasiswa.dashboard');
        }
        
        // Redirect default jika tidak punya peran di atas (misalnya ke homepage)
        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
