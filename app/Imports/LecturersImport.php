<?php

namespace App\Imports;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LecturersImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Gunakan transaksi database untuk memastikan data konsisten
            DB::transaction(function () use ($row) {
                // 1. Buat User baru dengan password acak
                $newUser = User::create([
                    'name'     => $row['nama_lengkap_dengan_gelar'],
                    'email'    => $row['email'],
                    'password' => Hash::make($row['nidn']), // Password acak, bisa direset nanti
                ]);

                // Tetapkan peran 'Dosen'
                $newUser->assignRole('Dosen');

                // 2. Buat data Dosen baru
                Lecturer::create([
                    'user_id'                 => $newUser->id,
                    'program_id'              => $row['program_studi_id'],
                    'nidn'                    => $row['nidn'],
                    'full_name_with_degree'   => $row['nama_lengkap_dengan_gelar'],
                ]);
            });
        }
    }

    public function rules(): array
    {
        return [
            'nidn' => 'required|numeric|unique:lecturers,nidn',
            'nama_lengkap_dengan_gelar' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'program_studi_id' => 'required|numeric|exists:programs,id',
        ];
    }
}