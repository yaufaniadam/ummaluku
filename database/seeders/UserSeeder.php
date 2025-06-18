<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Roles
        $roles = [
            'Super Admin',      // 
            'Admin PMB',        // 
            'Staf Akademik',    // 
            'Staf SDM',         // 
            'Eksekutif',        // 
            'Pegawai/Dosen',    // 
            'Mahasiswa',        // 
            'Camaru',           // 
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // 2. Membuat User untuk setiap Role Staf/Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        $adminPmb = User::create([
            'name' => 'Admin PMB',
            'email' => 'admin.pmb@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $adminPmb->assignRole('Admin PMB');

        $stafAkademik = User::create([
            'name' => 'Staf Akademik',
            'email' => 'staf.akademik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $stafAkademik->assignRole('Staf Akademik');

        $stafSdm = User::create([
            'name' => 'Staf SDM',
            'email' => 'staf.sdm@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $stafSdm->assignRole('Staf SDM');

        $eksekutif = User::create([
            'name' => 'Pimpinan Eksekutif',
            'email' => 'eksekutif@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $eksekutif->assignRole('Eksekutif');
        
        $dosen = User::create([
            'name' => 'Dosen Contoh',
            'email' => 'dosen@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $dosen->assignRole('Pegawai/Dosen');
    }
}