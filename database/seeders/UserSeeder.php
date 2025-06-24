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
            'Direktur Admisi',        // 
            'Staf Admisi',        // 
            'Direktur Akademik',    // 
            'Staf Akademik',    // 
            'Direktur SDM',         // 
            'Staf SDM',         // 
            'Eksekutif',        // 
            'Tendik',    // 
            'Dosen',    // 
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
            'name' => 'Direktur Admisi',
            'email' => 'dir.admisi@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $adminPmb->assignRole('Direktur Admisi');      

        $adminPmb = User::create([
            'name' => 'Staf Admisi',
            'email' => 'staf.admisi@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $adminPmb->assignRole('Staf Admisi');

        $adminPmb = User::create([
            'name' => 'Direktur Akademik',
            'email' => 'dir.akademik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $adminPmb->assignRole('Direktur Akademik');

        $stafAkademik = User::create([
            'name' => 'Staf Akademik',
            'email' => 'staf.akademik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $stafAkademik->assignRole('Staf Akademik');

        $adminPmb = User::create([
            'name' => 'Direktur SDM',
            'email' => 'dir.sdm@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $adminPmb->assignRole('Direktur SDM');

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
        $dosen->assignRole('Dosen');

        $dosen = User::create([
            'name' => 'Tendik Contoh',
            'email' => 'tendik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ]);
        $dosen->assignRole('Tendik');
    }
}