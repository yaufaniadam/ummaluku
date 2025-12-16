<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StaffImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                // Generate email dummy jika tidak ada (karena required di User)
                $email = $row['nip'] . '@staff.ac.id';

                // 1. Buat User baru
                $newUser = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $row['nama'],
                        'password' => Hash::make($row['nip']), // Password default = NIP
                    ]
                );

                // Tetapkan peran 'Tendik'
                if (!$newUser->hasRole('Tendik')) {
                    $newUser->assignRole('Tendik');
                }

                // 2. Buat data Staff baru
                Staff::create([
                    'user_id' => $newUser->id,
                    'nip'     => $row['nip'],
                    'phone'   => $row['no_hp'],
                    'gender'  => $row['jenis_kelamin'], // Asumsi input 'L' atau 'P'
                ]);
            });
        }
    }

    public function rules(): array
    {
        return [
            'nip'           => 'required|unique:staffs,nip',
            'nama'          => 'required|string',
            'no_hp'         => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
