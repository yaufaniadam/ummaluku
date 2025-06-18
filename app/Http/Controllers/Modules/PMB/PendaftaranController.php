<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman formulir pendaftaran.
     */
    public function index(Request $request)
    {
        // LANGKAH VALIDASI: Cek apakah parameter 'type' ada dan tidak kosong di URL.
        if (!$request->has('type') || !$request->query('type')) {
            // Jika tidak ada, kembalikan pengguna ke halaman utama dengan pesan error.
            return redirect()->route('home')->with('error', 'Silakan pilih salah satu jalur pendaftaran terlebih dahulu.');
        }
        // Mengambil data dari query parameter di URL
        // Contoh: /pendaftaran?type=prestasi&batch=1
        $categorySlug = $request->query('type');
        $batchId = $request->query('batch');

        // Mengirim data ini ke view "wadah" kita
        return view('pendaftaran.form', [
            'categorySlug' => $categorySlug,
            'batchId' => $batchId,
        ]);
    }
}