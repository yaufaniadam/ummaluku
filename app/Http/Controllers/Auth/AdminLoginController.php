<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        // Izinkan akses ke controller ini hanya untuk tamu (yang belum login) di guard 'admin'
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Menampilkan form login untuk admin.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba lakukan otentikasi menggunakan guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil...
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard')); // Arahkan ke dashboard admin
        }

        // 3. Jika gagal...
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}