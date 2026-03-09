<?php

namespace App\Livewire\Admin\Seleksi;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('adminlte::page')]
class Index extends Component
{
    use WithPagination;
    public array $selectedPrograms = [];

    public function saveAcceptanceDecision(Application $application)
    {
        // 1. Ambil ID prodi yang dipilih dari properti kita
        $selectedProgramId = $this->selectedPrograms[$application->id] ?? null;

        // 2. Validasi: pastikan admin sudah memilih prodi
        if (is_null($selectedProgramId)) {
            $this->dispatch('show-alert', [
                'message' => 'Silakan pilih program studi terlebih dahulu.', 
                'type' => 'error'
            ]);
            return;
        }

        // 3. Gunakan Transaction untuk memastikan semua update berhasil
        DB::transaction(function () use ($application, $selectedProgramId) {
            // Update status utama aplikasi menjadi 'accepted'
            $application->update(['status' => 'diterima']);

            // Loop semua pilihan prodi dari pendaftar ini
            foreach ($application->programChoices as $choice) {
                // Jika ID-nya cocok dengan yang dipilih admin, set is_accepted = true
                // Jika tidak, set is_accepted = false
                $choice->update(['is_accepted' => ($choice->program_id == $selectedProgramId)]);
            }

            // --- OTOMATISASI ---
            $student = \App\Models\Student::updateOrCreate(
                ['user_id' => $application->prospective->user_id],
                [
                    'program_id' => $selectedProgramId,
                    'nim' => $this->generateNim($application, $selectedProgramId),
                    'entry_year' => $application->batch->year,
                    'status' => 'Aktif',
                ]
            );

            $application->prospective->user->syncRoles(['Mahasiswa']);
            $this->autoEnrollSemesterOne($student, $application->batch->year);
        });

        // Kirim notifikasi sukses
        $this->dispatch('show-alert', [
            'message' => 'Pendaftar berhasil diterima di prodi yang dipilih.', 
            'type' => 'success'
        ]);
    }

    public function rejectApplicant(Application $application)
    {
        // Logika untuk menolak pendaftar
        $application->update(['status' => 'ditolak']);
        $this->dispatch('show-alert', ['message' => 'Pendaftar telah ditolak.', 'type' => 'error']);
    }

    public function render()
    {
        $applications = Application::with('prospective.user', 'programChoices.program')
            ->where('status', 'lolos_seleksi') // <-- Filter utama kita
            ->paginate(10);

        return view('livewire.admin.seleksi.index', [
            'applications' => $applications,
        ]);
    }

    private function generateNim($application, $programId): string
    {
        $year = $application->batch->year;
        $program = \App\Models\Program::find($programId);
        $sequence = \App\Models\Student::where('entry_year', $year)
            ->where('program_id', $programId)
            ->count() + 1;

        $sequentialNumber = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        return $year . ($program->code ?? '00') . $sequentialNumber;
    }

    private function autoEnrollSemesterOne($student, $entryYear)
    {
        $targetYearCode = $entryYear . '1';
        $academicYear = \App\Models\AcademicYear::where('year_code', $targetYearCode)->first();
        if (!$academicYear) return;

        $curriculum = \App\Models\Curriculum::where('program_id', $student->program_id)
            ->where('is_active', true)
            ->first();
        if (!$curriculum) return;

        $courses = $curriculum->courses()->wherePivot('semester', 1)->get();

        foreach ($courses as $course) {
            $courseClass = \App\Models\CourseClass::firstOrCreate(
                ['course_id' => $course->id, 'academic_year_id' => $academicYear->id],
                ['name' => 'Kelas A (Auto)', 'capacity' => 50, 'lecturer_id' => null]
            );

            \App\Models\ClassEnrollment::updateOrCreate(
                ['student_id' => $student->id, 'course_class_id' => $courseClass->id],
                ['academic_year_id' => $academicYear->id, 'status' => 'approved']
            );
        }
    }
}