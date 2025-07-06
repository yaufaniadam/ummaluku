<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === BUAT ROLES ===
        $superAdminRole = Role::create(['name' => 'Super Admin']); // Super Admin secara otomatis mendapat semua izin
        
        $dirAdmisiRole = Role::create(['name' => 'Direktur Admisi']);
        $stafAdmisiRole = Role::create(['name' => 'Staf Admisi']);
        
        $dirAkademikRole = Role::create(['name' => 'Direktur Akademik']);
        $stafAkademikRole = Role::create(['name' => 'Staf Akademik']);

        $dirSdmRole = Role::create(['name' => 'Direktur SDM']);
        $stafSdmRole = Role::create(['name' => 'Staf SDM']);

        $eksekutifRole = Role::create(['name' => 'Eksekutif']);
        $dosenRole = Role::create(['name' => 'Dosen']);
        $tendikRole = Role::create(['name' => 'Tendik']);
        
        $mahasiswaRole = Role::create(['name' => 'Mahasiswa']);
        Role::create(['name' => 'Camaru']);

        // === BERI PERMISSIONS KE ROLES ===
        $dirAdmisiRole->givePermissionTo(['view pmb', 'manage pmb']);
        $stafAdmisiRole->givePermissionTo(['view pmb']);

        $dirAkademikRole->givePermissionTo(['view akademik', 'manage akademik']);
        $stafAkademikRole->givePermissionTo(['view akademik']);

        $dirSdmRole->givePermissionTo(['view sdm', 'manage sdm']);
        $stafSdmRole->givePermissionTo(['view sdm']);
        
        $eksekutifRole->givePermissionTo(['view executive dashboard']);
        $mahasiswaRole->givePermissionTo(['mahasiswa']);


        // === BUAT USERS DAN TUGASKAN ROLE ===
        User::create([
            'name' => 'Super Admin',
            'email' => 'ummaluku.ac.id@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole($superAdminRole);

        User::create([
            'name' => 'Direktur Admisi',
            'email' => 'dir.admisi@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($dirAdmisiRole);

        User::create([
            'name' => 'Staf Admisi',
            'email' => 'admisi@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($stafAdmisiRole);

        User::create([
            'name' => 'Direktur Akademik',
            'email' => 'dir.akademik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($dirAkademikRole);

        User::create([
            'name' => 'Staf Akademik',
            'email' => 'staf.akademik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($stafAkademikRole);

        User::create([
            'name' => 'Direktur SDM',
            'email' => 'dir.sdm@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($dirSdmRole);

        User::create([
            'name' => 'Staf SDM',
            'email' => 'staf.sdm@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($stafSdmRole);

        User::create([
            'name' => 'Pimpinan Eksekutif',
            'email' => 'eksekutif@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($eksekutifRole);
        
        User::create([
            'name' => 'Dosen Contoh',
            'email' => 'dosen@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($dosenRole);

        User::create([
            'name' => 'Tendik Contoh',
            'email' => 'tendik@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($tendikRole);
    }
}