<?php

namespace App\Observers;

use App\Models\AcademicEvent;
use App\Models\AcademicYear;

class AcademicYearObserver
{
    /**
     * Handle the AcademicYear "created" event.
     */
    public function created(AcademicYear $academicYear): void
    {
        $this->syncKrsEvent($academicYear);
    }

    /**
     * Handle the AcademicYear "updated" event.
     */
    public function updated(AcademicYear $academicYear): void
    {
        $this->syncKrsEvent($academicYear);
    }

    /**
     * A helper method to create or update the KRS event.
     */
    protected function syncKrsEvent(AcademicYear $academicYear): void
    {
        // Secara otomatis membuat atau memperbarui event di kalender
        // berdasarkan tanggal KRS di tabel academic_years.
        AcademicEvent::updateOrCreate(
            [
                // Kunci untuk mencari: event untuk semester ini yang bernama 'Periode Pengisian KRS'
                'academic_year_id' => $academicYear->id,
                'name' => 'Periode Pengisian KRS'
            ],
            [
                // Nilai yang akan diisi atau diperbarui
                'start_date' => $academicYear->krs_start_date,
                'end_date' => $academicYear->krs_end_date,
                'description' => 'Periode pengisian dan perubahan Kartu Rencana Studi untuk mahasiswa.'
            ]
        );
    }
}