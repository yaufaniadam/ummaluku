<?php

namespace App\Livewire\Mahasiswa\Krs;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;
use App\Models\CourseClass;
use App\Models\FeeStructure;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PengisianKrs extends Component
{
    public Student $student;
    public ?AcademicYear $activeSemester;
    public $availableClasses;
    public $selectedClasses = [];
    public $sksLimit = 24; // Batas SKS default
    public $totalSks = 0;
    public $estimatedFee = 0;

    public $krsStatus = 'belum_mengisi';
    public $krsStatusMessage = '';
    public $krsStatusClass = 'info'; // Warna badge
    public $isKrsLocked = false;

    public function mount()
    {
        $this->student = Auth::user()->student;
        $this->activeSemester = AcademicYear::where('is_active', true)->first();

        if ($this->activeSemester) {
            // Cek status KRS yang sudah ada
            $firstEnrollment = ClassEnrollment::where('student_id', $this->student->id)
                ->where('academic_year_id', $this->activeSemester->id)
                ->first();

            if ($firstEnrollment) {
                $this->krsStatus = $firstEnrollment->status;
            }

            // Atur pesan dan status penguncian berdasarkan status KRS
            switch ($this->krsStatus) {
                case 'pending':
                    $this->krsStatusMessage = 'KRS Anda telah diajukan dan sedang menunggu persetujuan Dosen Pembimbing Akademik.';
                    $this->krsStatusClass = 'warning';
                    break;
                case 'approved':
                    $this->krsStatusMessage = 'KRS Anda untuk semester ini telah disetujui. Anda tidak dapat mengubahnya lagi.';
                    $this->krsStatusClass = 'success';
                    $this->isKrsLocked = true; // KUNCI FORM!
                    break;
                case 'rejected':
                    $this->krsStatusMessage = 'KRS Anda ditolak. Silakan perbaiki rencana studi Anda dan ajukan kembali.';
                    $this->krsStatusClass = 'danger';
                    break;
                default:
                    $this->krsStatusMessage = 'Silakan pilih kelas yang akan Anda ambil semester ini.';
                    $this->krsStatusClass = 'info';
                    break;
            }

            // Ambil kelas yang tersedia
            $this->availableClasses = CourseClass::where('academic_year_id', $this->activeSemester->id)
                ->whereHas('course.curriculums', function ($query) { // <-- UBAH DI SINI
                    $query->where('program_id', $this->student->program_id);
                })
                ->with(['course', 'lecturer'])
                ->get();

            // Ambil data KRS yang sudah pernah dipilih sebelumnya
            $existingEnrollments = ClassEnrollment::where('student_id', $this->student->id)
                ->where('academic_year_id', $this->activeSemester->id)
                ->with('courseClass.course')
                ->get();

            foreach ($existingEnrollments as $enrollment) {
                $this->selectedClasses[$enrollment->course_class_id] = $enrollment->courseClass;
            }

            $this->calculateTotalSks();
            $this->calculateTotalFee();
        }
    }

    public function selectClass($classId)
    {
        $class = $this->availableClasses->find($classId);

        // Validasi 1: Cek SKS Limit
        if (($this->totalSks + $class->course->sks) > $this->sksLimit) {
            $this->dispatch('error', message: 'Jumlah SKS melebihi batas maksimal Anda (' . $this->sksLimit . ' SKS).');
            return;
        }

        // Validasi 2: Cek Jadwal Bentrok
        if ($this->hasScheduleClash($class)) {
            $this->dispatch('error', message: 'Jadwal bentrok dengan kelas lain yang sudah Anda pilih.');
            return;
        }

        // Validasi 3: Cek Mata Kuliah yang sama
        foreach ($this->selectedClasses as $selected) {
            if ($selected->course_id === $class->course_id) {
                $this->dispatch('error', message: 'Anda sudah mengambil mata kuliah ini di kelas lain.');
                return;
            }
        }

        // Jika lolos semua validasi, tambahkan ke pilihan
        $this->selectedClasses[$classId] = $class;
        $this->calculateTotalSks();
        $this->calculateTotalFee();
        $this->dispatch('success', message: 'Kelas berhasil ditambahkan.');
    }

    public function removeClass($classId)
    {
        unset($this->selectedClasses[$classId]);
        $this->calculateTotalSks();
        $this->calculateTotalFee(); // <-- TAMBAHKAN BARIS INI
        $this->dispatch('success', message: 'Kelas berhasil dihapus.');
    }

    public function saveKrs()
    {
        if (empty($this->selectedClasses)) {
            $this->dispatch('error', message: 'Anda belum memilih kelas sama sekali.');
            return;
        }

        DB::transaction(function () {
            // Hapus data KRS lama untuk semester ini
            ClassEnrollment::where('student_id', $this->student->id)
                ->where('academic_year_id', $this->activeSemester->id)
                ->delete();

            // Buat data KRS baru berdasarkan pilihan
            foreach ($this->selectedClasses as $class) {
                ClassEnrollment::create([
                    'student_id' => $this->student->id,
                    'course_class_id' => $class->id,
                    'academic_year_id' => $this->activeSemester->id,
                    'status' => 'pending', // Status awal
                ]);
            }
        });

        session()->flash('success-page', 'KRS Anda berhasil diajukan dan menunggu persetujuan Dosen PA.');
        return $this->redirect(route('mahasiswa.krs.proses'), navigate: true);
    }

    private function calculateTotalSks()
    {
        $this->totalSks = collect($this->selectedClasses)->sum('course.sks');
    }

    private function hasScheduleClash($newClass)
    {
        if (is_null($newClass->day) || is_null($newClass->start_time)) {
            return false; // Anggap tidak bentrok jika jadwal tidak diset
        }

        foreach ($this->selectedClasses as $selectedClass) {
            if (
                !is_null($selectedClass->day) &&
                $selectedClass->day === $newClass->day &&
                $newClass->start_time < $selectedClass->end_time &&
                $newClass->end_time > $selectedClass->start_time
            ) {
                return true;
            }
        }
        return false;
    }

    private function calculateTotalFee()
    {
        $totalFee = 0;

        // Ambil komponen biaya TETAP untuk prodi & angkatan mahasiswa ini
        $fixedFeeComponent = FeeStructure::whereHas('feeComponent', fn($q) => $q->where('type', 'fixed'))
            ->where('program_id', $this->student->program_id)
            ->where('entry_year', $this->student->entry_year)
            ->first();

        if ($fixedFeeComponent) {
            $totalFee += $fixedFeeComponent->amount;
        }

        // Ambil komponen biaya PER SKS
        $sksFeeComponent = FeeStructure::whereHas('feeComponent', fn($q) => $q->where('type', 'per_sks'))
            ->where('program_id', $this->student->program_id)
            ->where('entry_year', $this->student->entry_year)
            ->first();

        if ($sksFeeComponent) {
            $totalFee += ($this->totalSks * $sksFeeComponent->amount);
        }

        // (Di masa depan, kita bisa tambahkan logika untuk biaya praktikum per mata kuliah di sini)

        $this->estimatedFee = $totalFee;
    }

    public function render()
    {
        return view('livewire.mahasiswa.krs.pengisian-krs');
    }
}
