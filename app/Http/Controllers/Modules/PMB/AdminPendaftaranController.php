<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPendaftaranController extends Controller
{
    /**
     * Menampilkan halaman utama untuk daftar pendaftar.
     */
    public function index()
    {
        return view('admin.pendaftaran.index');
    }
}