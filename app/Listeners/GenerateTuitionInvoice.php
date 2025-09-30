<?php

namespace App\Listeners;

use App\Events\KrsApproved;
use App\Models\AcademicInvoice;
use App\Models\AcademicInvoiceItem;
use App\Models\FeeStructure;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class GenerateTuitionInvoice
{
    /**
     * Handle the event.
     */
    public function handle(KrsApproved $event): void
    {
        $student = $event->student;
        $semester = $event->academicYear;

        // Cek dulu apakah tagihan untuk semester ini sudah ada, agar tidak duplikat
        $existingInvoice = AcademicInvoice::where('student_id', $student->id)
            ->where('academic_year_id', $semester->id)
            ->exists();
            
        if ($existingInvoice) {
            return; // Jika sudah ada, hentikan proses
        }

        // Mulai transaksi database
        DB::transaction(function () use ($student, $semester) {
            $totalAmount = 0;
            $invoiceItems = [];

            // 1. Ambil semua komponen biaya yang berlaku untuk prodi & angkatan mahasiswa
            $feeStructures = FeeStructure::where('program_id', $student->program_id)
                ->where('entry_year', $student->entry_year)
                ->with('feeComponent')
                ->get();

            // 2. Ambil total SKS yang disetujui dari KRS mahasiswa
            $totalSks = $student->enrollments()
                ->where('academic_year_id', $semester->id)
                ->where('status', 'approved')
                ->with('courseClass.course')
                ->get()
                ->sum('courseClass.course.sks');

            // 3. Proses setiap komponen biaya
            foreach ($feeStructures as $structure) {
                $amount = 0;
                $description = $structure->feeComponent->name;

                if ($structure->feeComponent->type === 'fixed') {
                    $amount = $structure->amount;
                } elseif ($structure->feeComponent->type === 'per_sks' && $totalSks > 0) {
                    $amount = $structure->amount * $totalSks;
                    $description .= " ({$totalSks} SKS)";
                }
                // (Di sini bisa ditambahkan logika untuk 'per_course' jika perlu)

                if ($amount > 0) {
                    $invoiceItems[] = [
                        'description' => $description,
                        'amount' => $amount,
                    ];
                    $totalAmount += $amount;
                }
            }

            // 4. Buat Invoice Header jika ada item tagihan
            if ($totalAmount > 0) {
                $invoice = AcademicInvoice::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $semester->id,
                    'invoice_number' => 'INV-' . $semester->year_code . '-' . $student->nim,
                    'total_amount' => $totalAmount,
                    'due_date' => now()->addDays(14), // Jatuh tempo 14 hari dari sekarang
                    'status' => 'unpaid',
                ]);

                // 5. Buat rincian tagihan
                foreach ($invoiceItems as $item) {
                    $invoice->items()->create($item);
                }
            }
        });
    }
}