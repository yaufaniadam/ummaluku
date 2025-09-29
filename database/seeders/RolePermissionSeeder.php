<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // === 1. BUAT PERMISSIONS YANG SPESIFIK/MIKRO ===
        $adminPermissions = [
            // Dashboard
            'view-executive-dashboard',
            // Settings
            'manage-settings',
            'manage-users',
            // Roles & Permissions
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            // Dosen
            'dosen-list',
            'dosen-create',
            'dosen-edit',
            'dosen-delete',
            'dosen-import',
            // Mahasiswa (Admin)
            'mahasiswa-list',
            'mahasiswa-create',
            'mahasiswa-edit',
            'mahasiswa-delete',
            'mahasiswa-import',
            // Kurikulum
            'kurikulum-list',
            'kurikulum-create',
            'kurikulum-edit',
            'kurikulum-delete',
            // Mata Kuliah
            'matakuliah-list',
            'matakuliah-create',
            'matakuliah-edit',
            'matakuliah-delete',
            'matakuliah-import',
            // Kelas Perkuliahan
            'kelas-list',
            'kelas-create',
            'kelas-edit',
            'kelas-delete',
            // Persetujuan KRS
            'krs-approve',
            // Input Nilai
            'nilai-input',
            
            // MODUL PENDAFTARAN MAHASISWA BARU (PMB)

            // Dasbor & Laporan
            'view pmb dashboard',
            'view pmb reports',

            // Manajemen Pendaftar
            'view applications',
            'view application details',
            'verify application documents',
            'update application status',
            'send notification to applicant',

            // Pengaturan Sistem
            'manage pmb settings',
            'manage batches',
            'manage admission categories',
            'finalize selection process',

            // Portal Pendaftar
            'access applicant portal',
            'update own biodata',
            'upload own documents',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Permissions untuk guard 'web' (Mahasiswa & Camaru)
        $webPermissions = [
            'mahasiswa-krs-fill',
            'mahasiswa-khs-view',
            'access applicant portal',
            'update own biodata',
            'upload own documents',
        ];
        
        foreach ($webPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }


        // === 2. BUAT ROLES ANDA ===
        $superAdminRole = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $eksekutifRole = Role::create(['name' => 'Eksekutif', 'guard_name' => 'admin']);
        $dirAkademikRole = Role::create(['name' => 'Direktur Akademik', 'guard_name' => 'admin']);
        $stafAkademikRole = Role::create(['name' => 'Staf Akademik', 'guard_name' => 'admin']);
        $dirAdmisiRole = Role::create(['name' => 'Direktur Admisi', 'guard_name' => 'admin']);
        $stafAdmisiRole = Role::create(['name' => 'Staf Admisi', 'guard_name' => 'admin']);
        $dirSdmRole = Role::create(['name' => 'Direktur SDM', 'guard_name' => 'admin']);
        $stafSdmRole = Role::create(['name' => 'Staf SDM', 'guard_name' => 'admin']);
        $dosenRole = Role::create(['name' => 'Dosen', 'guard_name' => 'admin']);
        $tendikRole = Role::create(['name' => 'Tendik', 'guard_name' => 'admin']);
        $mahasiswaRole = Role::create(['name' => 'Mahasiswa']);
        $camaruRole = Role::create(['name' => 'Camaru']);



        // === 3. BERI PERMISSION SPESIFIK KE ROLES ===
        $eksekutifRole->givePermissionTo(['view-executive-dashboard']);

        $dirAkademikRole->givePermissionTo([
            'dosen-list',
            'dosen-create',
            'dosen-edit',
            'dosen-delete',
            'dosen-import',
            'mahasiswa-list',
            'mahasiswa-create',
            'mahasiswa-edit',
            'mahasiswa-delete',
            'mahasiswa-import',
            'kurikulum-list',
            'kurikulum-create',
            'kurikulum-edit',
            'kurikulum-delete',
            'matakuliah-list',
            'matakuliah-create',
            'matakuliah-edit',
            'matakuliah-delete',
            'matakuliah-import',
            'kelas-list',
            'kelas-create',
            'kelas-edit',
            'kelas-delete',
        ]);

        $stafAkademikRole->givePermissionTo([
            'dosen-list',
            'dosen-create',
            'dosen-edit',
            'dosen-import',
            'mahasiswa-list',
            'mahasiswa-import',
            'kurikulum-list',
            'kurikulum-create',
            'kurikulum-edit',
            'matakuliah-list',
            'matakuliah-create',
            'matakuliah-edit',
            'matakuliah-import',
            'kelas-list',
            'kelas-create',
            'kelas-edit',
            'kelas-delete',
        ]);

        // Direktur Admisi: Bisa melakukan semua tugas operasional + pengaturan
        $dirAdmisiRole->givePermissionTo([
            'view pmb dashboard',
            'view pmb reports',
            'view applications',
            'view application details',
            'verify application documents',
            'update application status',
            'send notification to applicant',
            'manage pmb settings', // <-- Pembeda utama
            'manage batches',
            'manage admission categories',
            'finalize selection process',
        ]);

        // Staf Admisi: Hanya tugas operasional sehari-hari
        $stafAdmisiRole->givePermissionTo([
            'view pmb dashboard',
            'view applications',
            'view application details',
            'verify application documents',
            'update application status',
            'send notification to applicant',
        ]);

        // Camaru: Hanya bisa mengelola datanya sendiri
        $camaruRole->givePermissionTo([
            'access applicant portal',
            'update own biodata',
            'upload own documents',
        ]);

        $dosenRole->givePermissionTo([
            'krs-approve',
            'nilai-input',
        ]);

        $mahasiswaRole->givePermissionTo([
            'mahasiswa-krs-fill',
            'mahasiswa-khs-view',
        ]);

        // === 4. BUAT USERS BAWAAN ANDA ===
        User::create([
            'name' => 'Super Admin',
            'email' => 'ummaluku.ac.id@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole($superAdminRole);

        User::create([
            'name' => 'Pimpinan Eksekutif',
            'email' => 'eksekutif@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($eksekutifRole);

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
            'name' => 'Direktur Admisi',
            'email' => 'dir.admisi@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($dirAdmisiRole);

        User::create([
            'name' => 'Staf Admisi',
            'email' => 'staf.admisi@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($stafAdmisiRole);

        // User::create([
        //     'name' => 'Dosen Contoh',
        //     'email' => 'dosen@ummaluku.ac.id',
        //     'password' => Hash::make('password'),
        // ])->assignRole($dosenRole);

        // Buat user contoh untuk mahasiswa jika perlu
    }
}
