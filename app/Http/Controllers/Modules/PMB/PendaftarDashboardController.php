<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationProgramChoice;
use App\Models\Batch;
use App\Models\AdmissionCategory;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function showReApplyForm()
    {
        $user = Auth::user();

        // Cek apakah user berhak untuk re-apply (misal: aplikasi terakhir ditolak atau dibatalkan)
        // Atau kita biarkan saja mereka membuat aplikasi baru kapanpun?
        // Untuk amannya, kita cek status aplikasi terakhir
        $latestApplication = Application::whereHas('prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->first();

        if ($latestApplication && in_array($latestApplication->status, ['diterima', 'lakukan_pembayaran', 'lengkapi_data', 'upload_dokumen'])) {
            // Jika masih dalam proses aktif, mungkin sebaiknya tidak re-apply dulu
            // Kecuali kita mau membolehkan multiple active application.
            // Untuk skenario "tidak diterima", statusnya harus 'ditolak'.
            // Tapi user minta "masih boleh mendaftar kembali", jadi kita izinkan jika statusnya ditolak.
             if ($latestApplication->status !== 'ditolak') {
                 return redirect()->route('pendaftar')->with('error', 'Anda masih memiliki pendaftaran yang aktif.');
             }
        }

        // Ambil program studi
        $programs = Program::with('faculty')->orderBy('faculty_id')->get()->groupBy('faculty.name_id');

        return view('pendaftar.reapply-form', [
            'programs' => $programs
        ]);
    }

    public function storeReApply(Request $request)
    {
        $request->validate([
            'program_choice_1' => 'required|exists:programs,id',
            'program_choice_2' => 'nullable|exists:programs,id|different:program_choice_1',
        ]);

        $user = Auth::user();
        $prospective = $user->prospective;

        // Ambil Batch dan Kategori aktif default
        // Idealnya ini dipilih user, tapi untuk simplifikasi kita ambil yang aktif
        $batch = Batch::where('is_active', true)->firstOrFail();
        // Ambil kategori default, misal 'Reguler' atau ambil dari input jika ada
        // Kita ambil kategori pertama yang aktif saja untuk contoh, atau harusnya ada input
        // Asumsi: sistem punya setidaknya satu kategori aktif.
        $category = AdmissionCategory::where('is_active', true)->firstOrFail();

        DB::transaction(function () use ($prospective, $batch, $category, $request) {
             // 1. Buat Aplikasi Baru
             $application = Application::create([
                'prospective_id' => $prospective->id,
                'batch_id' => $batch->id,
                'admission_category_id' => $category->id,
                'registration_number' => 'TEMP-' . uniqid(),
                'status' => config('settings.payment_flow_enabled', false)
                    ? 'lakukan_pembayaran'
                    : 'lengkapi_data', // Langsung ke lengkapi data karena biodata sudah ada
            ]);

            // 2. Generate Registration Number
            $registrationNumber = 'PMB' . date('Y') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
            $application->update(['registration_number' => $registrationNumber]);

            // 3. Simpan Pilihan Prodi
            ApplicationProgramChoice::create([
                'application_id' => $application->id,
                'program_id' => $request->program_choice_1,
                'choice_order' => 1,
            ]);

            if ($request->program_choice_2) {
                ApplicationProgramChoice::create([
                    'application_id' => $application->id,
                    'program_id' => $request->program_choice_2,
                    'choice_order' => 2,
                ]);
            }
        });

        return redirect()->route('pendaftar')->with('success', 'Pendaftaran baru berhasil dibuat!');
    }
    
}