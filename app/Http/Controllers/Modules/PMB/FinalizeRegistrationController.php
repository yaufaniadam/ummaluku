<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinalizeRegistrationController extends Controller
{
    /**
     * Memfinalisasi pendaftar menjadi mahasiswa aktif.
     */
    public function finalize(Application $application)
    {
        // 1. Pengecekan Keamanan: Pastikan pendaftar sudah diterima dan pembayaran lunas.
        if ($application->status !== 'diterima' || $application->reRegistrationInvoice->status !== 'paid') {
            return back()->with('error', 'Pendaftar ini belum memenuhi syarat untuk difinalisasi.');
        }

        try {
            DB::transaction(function () use ($application) {
                // 2. Ambil data program studi tempat mahasiswa diterima
                $acceptedProgramId = $application->programChoices->where('is_accepted', true)->first()->program_id;

                // 3. Buat record baru di tabel 'students'
                $student = Student::create([
                    'user_id' => $application->prospective->user_id,
                    'program_id' => $acceptedProgramId,
                    'nim' => $this->generateNim($application, $acceptedProgramId), // Hasilkan NIM
                    'entry_year' => $application->batch->year,
                    'status' => 'Aktif',
                ]);

                // 4. Ubah Role User dari 'Camaru' menjadi 'Mahasiswa'
                $application->prospective->user->syncRoles(['Mahasiswa']);

                // 5. Update Status Aplikasi menjadi Selesai
                $application->update(['status' => 'completed']);

                // Di sini nanti bisa ditambahkan logika untuk mendaftarkan "Paket Mata Kuliah" semester 1
                // ke dalam tabel 'student_courses' jika diperlukan.
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat finalisasi: ' . $e->getMessage());
        }

        return redirect()->route('admin.diterima.index')->with('success', 'Mahasiswa berhasil difinalisasi dan NIM telah dibuat.');
    }

    /**
     * Logika untuk membuat NIM.
     * Contoh: 25(Tahun Masuk) . 11(Kode Prodi) . 001(No Urut)
     */
    private function generateNim(Application $application, int $programId): string
    {
        // 1. Ambil tahun masuk (penuh, bukan hanya 2 digit)
        $year = $application->batch->year;

        // 2. Ambil objek program studi untuk mendapatkan kodenya
        $program = Program::find($programId);
        if (!$program || !$program->code) {
            // Fallback jika kode prodi belum diatur
            throw new \Exception('Kode untuk program studi ' . ($program->name_id ?? '') . ' belum diatur.');
        }
        $programCode = $program->code;

        // 3. Hitung nomor urut mahasiswa di prodi dan tahun yang sama
        $sequence = Student::where('entry_year', $year)
            ->where('program_id', $programId)
            ->count() + 1;

        $sequentialNumber = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        // 4. Gabungkan semuanya sesuai format baru
        return $year . $programCode . $sequentialNumber;
    }
}
