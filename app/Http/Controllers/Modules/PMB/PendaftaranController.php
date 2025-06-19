<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Batch;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman formulir pendaftaran.
     */
    // public function index(Request $request)
    // {
    //     // LANGKAH VALIDASI: Cek apakah parameter 'type' ada dan tidak kosong di URL.
    //     if (!$request->has('type') || !$request->query('type')) {
    //         // Jika tidak ada, kembalikan pengguna ke halaman utama dengan pesan error.
    //         return redirect()->route('home')->with('error', 'Silakan pilih salah satu jalur pendaftaran terlebih dahulu.');
    //     }
    //     // Mengambil data dari query parameter di URL
    //     // Contoh: /pendaftaran?type=prestasi&batch=1
    //     $categorySlug = $request->query('type');
    //     $batchId = $request->query('batch');

    //     // Mengirim data ini ke view "wadah" kita
    //     return view('pendaftaran.form', [
    //         'categorySlug' => $categorySlug,
    //         'batchId' => $batchId,
    //     ]);
    // }

    public function index(Request $request)
    {
        // Ambil slug dan id dari URL
        $categorySlug = $request->query('type');
        $batchId = $request->query('batch');

        // Validasi: Jika slug atau id tidak ada, kembalikan ke home
        if (!$categorySlug || !$batchId) {
            return redirect()->route('home')->with('error', 'Silakan pilih jalur pendaftaran yang valid.');
        }

        // Ambil data lengkap dari database berdasarkan slug dan id
        $category = AdmissionCategory::where('slug', $categorySlug)->firstOrFail();
        $batch = Batch::find($batchId);

        // Jika salah satu tidak ditemukan, kembalikan juga ke home
        if (!$batch) {
            return redirect()->route('home')->with('error', 'Gelombang pendaftaran tidak ditemukan.');
        }

        // Kirim semua data yang kita butuhkan ke view "wadah"
        return view('pendaftaran.form', [
            'category' => $category,
            'batch' => $batch,
        ]);
    }

    public function showCategoryDetail(AdmissionCategory $category)
    {
        // Load relasi gelombang yang aktif saja untuk kategori ini
        $category->load(['batches' => function ($query) {
            $query->where('is_active', true);
        }]);

        // Tampilkan view baru, kirim data kategori beserta gelombangnya
        return view('pendaftaran.category-detail', ['category' => $category]);
    }
}
