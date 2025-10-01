<?php

namespace App\Imports;

use App\Models\Prospective;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class OldStudentsImport implements ToCollection, WithHeadingRow, WithValidation, WithCustomCsvSettings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';' // Ganti menjadi titik koma jika file Anda menggunakan itu
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                // 1. Buat User baru
                $newUser = User::create([
                    'name'     => $row['nama_lengkap'],
                    'email'    => $row['email'],
                    'password' => Hash::make($row['nim']), // Password acak
                ]);
                $newUser->assignRole('Mahasiswa'); // Pastikan role 'Mahasiswa' ada

                // 2. Buat Prospective (untuk biodata)
                Prospective::create([
                    'user_id' => $newUser->id,
                    'registration_source' => 'migrasi_lama',
                    // 'full_name' => $row['nama_lengkap'],
                    // 'email' => $row['email'],
                    'id_number' => $row['nik'] ?? null,
                    'birth_place' => $row['tempat_lahir'] ?? null,
                    'birth_date' => $row['tanggal_lahir'] ?? null,
                    'gender' => $row['jenis_kelamin'] ?? null,
                    'father_name' => $row['nama_ayah'] ?? null,
                    'mother_name' => $row['nama_ibu'] ?? null,
                    'address' => $row['alamat_lengkap'] ?? null,
                    'phone' => $row['no_hp'] ?? null,
                    // Isi kolom lain di `prospectives` jika ada di Excel
                ]);

                // 3. Buat Student (untuk data akademik)
                Student::create([
                    'user_id' => $newUser->id,
                    'nim' => $row['nim'],
                    'program_id' => $row['program_studi_id'],
                    'entry_year' => $row['tahun_masuk'],
                    'status' => $row['status_awal'],
                ]);
            });
        }
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|numeric|unique:students,nim',
            'program_studi_id' => 'required|integer|exists:programs,id',
            'tahun_masuk' => 'required|digits:4',
            'status_awal' => 'required|in:active,on_leave,graduated,dropped_out',
        ];
    }
}