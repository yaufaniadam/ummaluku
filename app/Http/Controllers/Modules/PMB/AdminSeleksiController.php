<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Notifications\MahasiswaDiterima;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\ReRegistrationInvoice;
use App\Models\Setting;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Program;
use App\Models\Curriculum;
use App\Models\CourseClass;
use App\Models\ClassEnrollment;

class AdminSeleksiController extends Controller
{
    /**
     * Menampilkan halaman utama Proses Seleksi.
     */
    public function index()
    {
        return view('admin.seleksi.index');
    }

    /**
     * Menyediakan data untuk DataTables.
     */
    public function data()
    {
        // Ambil data pendaftar yang siap seleksi
        $query = Application::with(['prospective.user', 'programChoices.program'])
            ->where('status', 'lolos_verifikasi_data');

        return DataTables::of($query)
            // Tambah kolom nomor urut
            ->addIndexColumn()
            // Edit kolom pilihan prodi agar formatnya lebih rapi
            ->editColumn('program_choices', function ($application) {
                $choices = '';
                foreach ($application->programChoices as $choice) {
                    $choices .= '<div>Pilihan ' . $choice->choice_order . ': ' . $choice->program->name_id . '</div>';
                }
                return $choices;
            })
            // Tambah kolom baru untuk Aksi
            ->addColumn('action', function ($application) {
                // ---- Form untuk Aksi Terima ----
                $options = '<option value="">-- Pilih Prodi --</option>';
                foreach ($application->programChoices as $choice) {
                    $options .= '<option value="' . $choice->program_id . '">' . $choice->program->name_id . '</option>';
                }
                $acceptForm = '
                    <form action="' . route('admin.pmb.seleksi.accept', $application) . '" method="POST" class="d-inline-block">
                        ' . csrf_field() . '
                        <div class="input-group">
                            <select name="program_id" class="form-control form-control-sm" required>
                                ' . $options . '
                            </select>
                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                        </div>
                    </form>
                ';

                // ---- Form untuk Aksi Tolak ----
                $rejectForm = '
                    <form action="' . route('admin.pmb.seleksi.reject', $application) . '" method="POST" class="d-inline-block ms-1">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin MENOLAK pendaftar ini?\')">Tolak</button>
                    </form>
                ';

                // Gabungkan kedua form
                return '<div class="d-flex">' . $acceptForm . $rejectForm . '</div>';
            })
            // Beritahu Yajra bahwa kolom 'program_choices' dan 'action' berisi HTML
            ->rawColumns(['program_choices', 'action'])
            ->make(true);
    }

    public function accept(Request $request, Application $application)
    {
        // 1. Validasi input dari form
        $request->validate([
            'program_id' => 'required|exists:programs,id'
        ]);

        $selectedProgramId = $request->input('program_id');

        // 2. Gunakan Transaction untuk keamanan data
        DB::transaction(function () use ($application, $selectedProgramId) {
            // Update status utama aplikasi
            $application->update(['status' => 'diterima']);

            // Update status di setiap pilihan prodi
            foreach ($application->programChoices as $choice) {
                $choice->update(['is_accepted' => ($choice->program_id == $selectedProgramId)]);
            }

            // --- OTOMATISASI: Buat Mahasiswa & Enroll Mata Kuliah ---
            // Kita panggil logic dari FinalizeRegistration logic (atau buat helper)
            // Namun untuk cepat dan direct sesuai request "setelah diterima":
            $student = \App\Models\Student::updateOrCreate(
                ['user_id' => $application->prospective->user_id],
                [
                    'program_id' => $selectedProgramId,
                    'nim' => $this->generateNim($application, $selectedProgramId), // Hasilkan NIM
                    'entry_year' => $application->batch->year,
                    'status' => 'Aktif',
                ]
            );

            // Ubah Role User dari 'Camaru' menjadi 'Mahasiswa'
            $application->prospective->user->syncRoles(['Mahasiswa']);

            // Otomatis Enroll Mata Kuliah Semester 1
            $this->autoEnrollSemesterOne($student, $application->batch->year);
        });

       // Ambil total biaya dari settings
        $totalAmount = (int) Setting::where('key', 're_registration_fee')->first()->value;

        // Buat invoice induk
        ReRegistrationInvoice::updateOrCreate(
            ['application_id' => $application->id], // Cari berdasarkan application_id
            [
                'invoice_number' => 'REG-' . $application->batch->year . '-' . $application->registration_number,
                'total_amount' => $totalAmount,
                'due_date' => now()->addMonths(3), // Batas akhir semua cicilan
                'status' => 'unpaid',
            ]
        );     

        $application->prospective->user->notify(new MahasiswaDiterima($application));

        // 3. Kembalikan ke halaman seleksi dengan pesan sukses
        return back()->with('success', 'Keputusan penerimaan untuk ' . $application->prospective->user->name . ' berhasil disimpan.');
    }
    public function reject(Application $application)
    {
        $application->update(['status' => 'ditolak']);

        return back()->with('success', 'Pendaftar ' . $application->prospective->user->name . ' telah ditolak.');
    }

    /**
     * Logika untuk membuat NIM.
     * Copied from FinalizeRegistrationController
     */
    private function generateNim(Application $application, int $programId): string
    {
        $year = $application->batch->year;
        $program = Program::find($programId);
        if (!$program || !$program->code) {
            throw new \Exception('Kode untuk program studi ' . ($program->name_id ?? '') . ' belum diatur.');
        }
        $programCode = $program->code;

        $sequence = Student::where('entry_year', $year)
            ->where('program_id', $programId)
            ->count() + 1;

        $sequentialNumber = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        return $year . $programCode . $sequentialNumber;
    }

    /**
     * Otomatis mendaftarkan mahasiswa ke mata kuliah semester 1.
     * Copied from FinalizeRegistrationController
     */
    private function autoEnrollSemesterOne(Student $student, int $entryYear)
    {
        $targetYearCode = $entryYear . '1';
        $academicYear = AcademicYear::where('year_code', $targetYearCode)->first();

        if (!$academicYear) {
            return; // Skip if academic year not found
        }

        $curriculum = Curriculum::where('program_id', $student->program_id)
            ->where('is_active', true)
            ->first();

        if (!$curriculum) {
            return;
        }

        $semesterOneCourses = $curriculum->courses()
            ->wherePivot('semester', 1)
            ->get();

        foreach ($semesterOneCourses as $course) {
            $courseClass = CourseClass::where('course_id', $course->id)
                ->where('academic_year_id', $academicYear->id)
                ->first();

            if (!$courseClass) {
                $courseClass = CourseClass::create([
                    'course_id' => $course->id,
                    'academic_year_id' => $academicYear->id,
                    'lecturer_id' => null,
                    'name' => 'Kelas A (Auto)',
                    'capacity' => 50,
                ]);
            }

            ClassEnrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'course_class_id' => $courseClass->id,
                ],
                [
                    'academic_year_id' => $academicYear->id,
                    'status' => 'approved',
                ]
            );
        }
    }
}
