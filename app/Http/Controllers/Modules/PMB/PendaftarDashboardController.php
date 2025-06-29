<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftarDashboardController extends Controller
{
    public function showDashboard()
    {
        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Ambil data pendaftaran (application) terbaru milik user tersebut.
        // Kita gunakan 'with()' agar semua data relasi (seperti biodata, jalur, gelombang)
        // ikut terambil secara efisien.
        $application = Application::whereHas('prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['prospective', 'batch', 'admissionCategory'])
        ->latest() // Ambil yang paling baru jika ada lebih dari satu
        ->first();

        // 4. Jika pendaftar ini belum memiliki data aplikasi sama sekali, arahkan ke home.
        if (!$application) {
            return redirect()->route('home')->with('error', 'Anda belum melakukan pendaftaran awal.');
        }

        // 5. Kirim data aplikasi yang sudah lengkap dengan relasinya ke view.
        return view('pendaftar.dashboard', [
            'application' => $application
        ]);
    }
    
}