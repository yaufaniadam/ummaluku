<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'program_id',
        'nim',
        'entry_year',
        'status',
        'academic_advisor_id',
    ];

    /**
     * Mendapatkan data user yang terkait dengan mahasiswa ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan data program studi dari mahasiswa ini.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Mendapatkan data dosen pembimbing akademik.
     * Relasi ini juga ke model User, karena Dosen adalah User.
     */
    public function academicAdvisor(): BelongsTo
    {
    return $this->belongsTo(Lecturer::class, 'academic_advisor_id');
    }

    /**
     * Get all enrollments for the student.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    public function getCurrentSemester(): int
    {
        // Cari dulu semester yang sedang aktif di sistem
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        
        // Jika tidak ada semester aktif, kembalikan 0
        if (!$activeAcademicYear) {
            return 0;
        }
        
        // Ambil tahun dari kode tahun ajaran (misal: '20251' -> 2025)
        $currentYear = substr($activeAcademicYear->year_code, 0, 4);

        // Hitung selisih tahun
        $yearDifference = (int)$currentYear - (int)$this->entry_year;

        // Hitung semester
        $semester = ($yearDifference * 2) + ($activeAcademicYear->semester_type === 'Ganjil' ? 1 : 2);
        
        return $semester > 0 ? $semester : 1;
    }

    /**
     * Menghitung total SKS yang sudah lulus.
     */
    public function getTotalPassedSks(): int
    {
        return $this->enrollments()
            ->where('grade_index', '>', 0) // Hanya hitung yang nilainya tidak E
            ->with('courseClass.course')
            ->get()
            ->sum('courseClass.course.sks');
    }

    /**
     * Menghitung Indeks Prestasi Kumulatif (IPK).
     */
    public function getCumulativeGpa(): float
    {
        $enrollmentsWithGrades = $this->enrollments()
            ->whereNotNull('grade_index')
            ->with('courseClass.course')
            ->get();

        if ($enrollmentsWithGrades->isEmpty()) {
            return 0.00;
        }
        
        $totalWeight = $enrollmentsWithGrades->sum(function ($enrollment) {
            return $enrollment->grade_index * $enrollment->courseClass->course->sks;
        });

        $totalSks = $enrollmentsWithGrades->sum('courseClass.course.sks');

        if ($totalSks == 0) {
            return 0.00;
        }

        return round($totalWeight / $totalSks, 2);
    }
}