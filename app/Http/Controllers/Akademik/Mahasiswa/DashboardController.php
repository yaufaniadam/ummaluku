<?php

namespace App\Http\Controllers\Akademik\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Ambil data student yang terhubung dengan user ini
        // Kita 'with()' untuk mengambil nama prodinya juga secara efisien
        $student = $user->student()->with('program')->firstOrFail();

        // Tampilkan view dashboard mahasiswa dan kirim datanya
        return view('akademik.mahasiswa.dashboard', compact('student'));
    }
}