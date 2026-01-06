<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class NimGeneratorService
{
    /**
     * Generate NIM securely without race conditions.
     * Uses database locking and sequence table to ensure uniqueness.
     *
     * @param Application $application
     * @param int $programId
     * @return string
     * @throws \Exception
     */
    public function generate(Application $application, int $programId): string
    {
        return DB::transaction(function () use ($application, $programId) {
            $year = $application->batch->year;
            
            // Get program code
            $program = Program::find($programId);
            if (!$program || !$program->code) {
                throw new \Exception('Kode untuk program studi ' . ($program->name_id ?? '') . ' belum diatur.');
            }
            $programCode = $program->code;
            
            // Get or create sequence entry with lock
            $sequence = DB::table('nim_sequences')
                ->where('program_id', $programId)
                ->where('entry_year', $year)
                ->lockForUpdate()  // CRITICAL: Prevents race condition
                ->first();
            
            if (!$sequence) {
                // First student in this program/year
                DB::table('nim_sequences')->insert([
                    'program_id' => $programId,
                    'entry_year' => $year,
                    'last_sequence' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $sequenceNumber = 1;
            } else {
                // Increment sequence
                $sequenceNumber = $sequence->last_sequence + 1;
                DB::table('nim_sequences')
                    ->where('id', $sequence->id)
                    ->update([
                        'last_sequence' => $sequenceNumber,
                        'updated_at' => now(),
                    ]);
            }
            
            // Format: YYYY + CODE + SEQUENTIAL (3 digits)
            // Example: 2026 + 11 + 001 = 202611001
            $sequentialNumber = str_pad($sequenceNumber, 3, '0', STR_PAD_LEFT);
            return $year . $programCode . $sequentialNumber;
        });
    }
    
    /**
     * Get the next available NIM preview (without actually generating it).
     *
     * @param int $programId
     * @param int $entryYear
     * @return string
     */
    public function preview(int $programId, int $entryYear): string
    {
        $program = Program::find($programId);
        if (!$program || !$program->code) {
            return 'N/A';
        }
        
        $sequence = DB::table('nim_sequences')
            ->where('program_id', $programId)
            ->where('entry_year', $entryYear)
            ->first();
        
        $nextSequence = $sequence ? $sequence->last_sequence + 1 : 1;
        $sequentialNumber = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        
        return $entryYear . $program->code . $sequentialNumber;
    }
}
