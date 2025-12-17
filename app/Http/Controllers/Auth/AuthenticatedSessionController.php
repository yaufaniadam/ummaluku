<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'portal' => null,
            'title' => 'Login',
            'theme' => 'primary'
        ]);
    }

    public function createMahasiswa(): View
    {
        return view('auth.login', [
            'portal' => 'mahasiswa',
            'title' => 'Login Mahasiswa',
            'theme' => 'primary'
        ]);
    }

    public function createStaff(): View
    {
        return view('auth.login', [
            'portal' => 'staff',
            'title' => 'Login Dosen & Tendik',
            'theme' => 'success'
        ]);
    }

    public function createAdmin(): View
    {
        return view('auth.login', [
            'portal' => 'admin',
            'title' => 'Login Administrator',
            'theme' => 'dark'
        ]);
    }

    public function createCamaru(): View
    {
        return view('auth.login', [
            'portal' => 'camaru',
            'title' => 'Login Calon Mahasiswa',
            'theme' => 'warning'
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        $user = $request->user();
        $portal = $request->input('portal');

        // Strict Role Check based on Portal
        if ($portal) {
            $allowed = false;
            switch ($portal) {
                case 'mahasiswa':
                    if ($user->hasRole('Mahasiswa')) $allowed = true;
                    break;
                case 'staff':
                    // Check if user has any staff-related role (Dosen, Tendik, etc.)
                    // Assuming 'Dosen' is a role. For Tendik, usually they have specific roles or just 'Staff'.
                    // Based on memory: "Staff (Tendik) ... automatically generating a User account".
                    // "Staff model ... relies on associated User".
                    // "User Management ... 'Staf' (catch-all for all other roles including Super Admin...)".
                    // Ideally, we should check for Dosen or non-student/non-camaru roles.
                    // For now, I'll allow Dosen and any role that isn't Mahasiswa/Camaru/Super Admin if explicitly staff?
                    // Simpler: Allow 'Dosen', 'Direktur...', 'Staf...' roles.
                    // Or simply: NOT Mahasiswa and NOT Camaru?
                    // Let's list common staff roles:
                    $staffRoles = ['Dosen', 'Direktur SDM', 'Staf SDM', 'Direktur Keuangan', 'Staf Keuangan', 'Direktur Admisi', 'Staf Admisi', 'Kaprodi', 'Sekretaris Prodi', 'Staf Prodi', 'Super Admin'];

                    if ($user->hasRole($staffRoles)) $allowed = true;
                    if ($user->staff) $allowed = true;
                    if ($user->lecturer) $allowed = true;

                    break;
                case 'admin':
                    // Admin portal: Super Admin, Admission Admin, SDM Admin, Finance Admin?
                    // Usually "Admin" implies high level access or backoffice.
                    // Let's allow roles that have access to AdminLTE dashboard logic.
                    if ($user->hasRole(['Super Admin', 'Direktur Admisi', 'Staf Admisi', 'Direktur SDM', 'Staf SDM', 'Direktur Keuangan', 'Staf Keuangan', 'Kaprodi', 'Staf Prodi'])) {
                        $allowed = true;
                    }
                    break;
                case 'camaru':
                    if ($user->hasRole('Camaru')) $allowed = true;
                    break;
            }

            if (!$allowed) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => __('Anda tidak memiliki hak akses untuk login melalui portal ini.'),
                ]);
            }
        }

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
        // If strict portal check passed but no specific dashboard found (unlikely), fallback.
        // Use 'pmb.home' alias or 'gateway'.
        return redirect()->intended(route('gateway'));
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
