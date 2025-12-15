<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Application;
use App\Models\ClassEnrollment;
use App\Models\CourseClass;
use App\Models\Curriculum;
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

        $warningMessage = '';

        try {
            DB::transaction(function () use ($application, &$warningMessage) {
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
                $application->update(['status' => 'sudah_registrasi']);

                // 6. Otomatis Enroll Mata Kuliah Semester 1
                try {
                    $this->autoEnrollSemesterOne($student, $application->batch->year);
                } catch (\Exception $e) {
                    // Jika auto enroll gagal, jangan batalkan transaksi utama, tapi catat errornya
                    // Log::error('Auto enroll failed: ' . $e->getMessage());
                    $warningMessage = ' Namun, gagal mendaftarkan mata kuliah otomatis: ' . $e->getMessage();
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat finalisasi: ' . $e->getMessage());
        }

        return redirect()->route('admin.pmb.diterima.index')->with('success', 'Mahasiswa berhasil difinalisasi dan NIM telah dibuat.' . $warningMessage);
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

    /**
     * Otomatis mendaftarkan mahasiswa ke mata kuliah semester 1.
     * Jika kelas belum ada, buatkan kelas default tanpa dosen.
     */
    private function autoEnrollSemesterOne(Student $student, int $entryYear)
    {
        // 1. Tentukan Tahun Akademik Semester 1 (Ganjil) untuk angkatan ini
        // Format kode: YYYY1 (Contoh: 20251)
        $targetYearCode = $entryYear . '1';
        $academicYear = AcademicYear::where('year_code', $targetYearCode)->first();

        if (!$academicYear) {
            // Jika tahun akademik belum dibuat, lewati proses ini (tidak error, hanya skip)
            throw new \Exception("Tahun Akademik $targetYearCode belum dibuat. Harap hubungi Admin Akademik.");
        }

        // 2. Cari Kurikulum Aktif untuk Program Studi ini
        $curriculum = Curriculum::where('program_id', $student->program_id)
            ->where('is_active', true)
            ->first();

        if (!$curriculum) {
            throw new \Exception("Belum ada Kurikulum Aktif untuk Program Studi ini.");
        }

        // 3. Ambil Mata Kuliah Semester 1 dari Kurikulum tersebut
        // Menggunakan relasi BelongsToMany 'courses' dengan pivot 'semester'
        $semesterOneCourses = $curriculum->courses()
            ->wherePivot('semester', 1)
            ->get();

        if ($semesterOneCourses->isEmpty()) {
            throw new \Exception("Tidak ada mata kuliah Semester 1 di kurikulum aktif.");
        }

        foreach ($semesterOneCourses as $course) {
            // 4. Cek apakah sudah ada kelas untuk mata kuliah ini di tahun akademik tersebut
            // Ambil kelas pertama yang ditemukan (asumsi default class)
            $courseClass = CourseClass::where('course_id', $course->id)
                ->where('academic_year_id', $academicYear->id)
                ->first();

            // 5. Jika kelas belum ada, buat kelas baru (Auto-Create)
            if (!$courseClass) {
                $courseClass = CourseClass::create([
                    'course_id' => $course->id,
                    'academic_year_id' => $academicYear->id,
                    'lecturer_id' => null, // Boleh null (TBA) sesuai kesepakatan
                    'name' => 'Kelas A (Auto)', // Penanda kelas otomatis
                    'capacity' => 50, // Default capacity
                ]);
            }

            // 6. Enroll Mahasiswa ke Kelas tersebut
            // Cek dulu biar gak duplikat (safety check)
            $existingEnrollment = ClassEnrollment::where('student_id', $student->id)
                ->where('course_class_id', $courseClass->id)
                ->exists();

            if (!$existingEnrollment) {
                ClassEnrollment::create([
                    'student_id' => $student->id,
                    'course_class_id' => $courseClass->id,
                    'academic_year_id' => $academicYear->id, // Wajib diisi untuk pelaporan
                    'status' => 'approved', // Langsung setujui karena ini paket semester 1
                ]);
            }
        }
    }
}
