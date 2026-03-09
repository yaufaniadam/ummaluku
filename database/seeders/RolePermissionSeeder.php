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
        $permissions = [
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

            //Portal Mahasiswa
            'mahasiswa-krs-fill',
            'mahasiswa-khs-view',

            // Keuangan
            'biaya-list',
            'biaya-create',
            'biaya-edit',
            'tagihan-list',
            'tagihan-create',
            'tagihan-edit',
            'pembayaran-list',
            'pembayaran-create',
            'pembayaran-edit',
            'pembayaran-delete',
            'pembayaran-confirm',

            // Dir Keuangan            
            'biaya-delete',
            'tagihan-delete',
            'pengembalian-pembayaran',
            'pengaturan-keuangan',
            'laporan-keuangan',

            // Dir SDM

            'dosen-list',
            'dosen-create',
            'dosen-edit',
            'dosen-delete',
            'dosen-import',
            'tendik-list',
            'tendik-create',
            'tendik-edit',
            'tendik-delete',
            'tendik-import',
            'kontrak-manage',
            'laporan-sdm',
            'jabatan-manage',

        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // === 2. BUAT ROLES ANDA ===
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $eksekutifRole = Role::create(['name' => 'Eksekutif']);
        $dirAkademikRole = Role::create(['name' => 'Direktur Akademik']);
        $stafAkademikRole = Role::create(['name' => 'Staf Akademik']);
        $dirAdmisiRole = Role::create(['name' => 'Direktur Admisi']);
        $stafAdmisiRole = Role::create(['name' => 'Staf Admisi']);
        $dirSdmRole = Role::create(['name' => 'Direktur SDM']);
        $stafSdmRole = Role::create(['name' => 'Staf SDM']);
        $dirKeuanganRole = Role::create(['name' => 'Direktur Keuangan']);
        $stafKeuanganRole = Role::create(['name' => 'Staf Keuangan']);
        $kaprodiRole = Role::firstOrCreate(['name' => 'Kaprodi']);
        $stafProdiRole = Role::firstOrCreate(['name' => 'Staf Prodi']);
        $dosenRole = Role::firstOrCreate(['name' => 'Dosen']);
        $tendikRole = Role::firstOrCreate(['name' => 'Tendik']);
        $mahasiswaRole = Role::firstOrCreate(['name' => 'Mahasiswa']);
        $camaruRole = Role::firstOrCreate(['name' => 'Camaru']);
        $dekanRole = Role::firstOrCreate(['name' => 'Dekan']);
        $stafFakRole = Role::firstOrCreate(['name' => 'Staf Fakultas']);



        // === 3. BERI PERMISSION SPESIFIK KE ROLES ===
        $eksekutifRole->givePermissionTo(['view-executive-dashboard']);

        $dirSdmRole->givePermissionTo([
            'dosen-list',
            'dosen-create',
            'dosen-edit',
            'dosen-delete',
            'dosen-import',
            'tendik-list',
            'tendik-create',
            'tendik-edit',
            'tendik-delete',
            'tendik-import',
            'kontrak-manage',
            'laporan-sdm',
            'jabatan-manage',
        ]);

        $stafSdmRole->givePermissionTo([
            'dosen-list',
            'dosen-create',
            'dosen-edit',
            'tendik-list',
            'tendik-create',
            'tendik-edit',
            'kontrak-manage',
            'laporan-sdm',
        ]);


        $dirAkademikRole->givePermissionTo([

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

        $dirKeuanganRole->givePermissionTo([
            'biaya-list',
            'biaya-create',
            'biaya-edit',
            'tagihan-list',
            'tagihan-create',
            'tagihan-edit',
            'pembayaran-list',
            'pembayaran-create',
            'pembayaran-edit',
            'pembayaran-delete',
            'pembayaran-confirm',
            'biaya-delete',
            'tagihan-delete',
            'pengembalian-pembayaran',
            'pengaturan-keuangan',
            'laporan-keuangan',
        ]);

        $stafKeuanganRole->givePermissionTo([
            'biaya-list',
            'biaya-create',
            'biaya-edit',
            'tagihan-list',
            'tagihan-create',
            'tagihan-edit',
            'pembayaran-list',
            'pembayaran-create',
            'pembayaran-edit',
            'pembayaran-delete',
            'pembayaran-confirm',
        ]);

        $stafProdiRole->givePermissionTo([
            'kurikulum-list',
            'kurikulum-create',
            'kurikulum-edit',
            'kelas-list',
            'kelas-create',
            'kelas-edit',
            'mahasiswa-list',
        ]);

        $kaprodiRole->syncPermissions($stafProdiRole->permissions); // Beri semua izin staf
        $kaprodiRole->givePermissionTo([ // Tambahkan izin khusus Kaprodi
            'krs-approve',
            'nilai-input',
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

        User::create([
            'name' => 'Direktur Keuangan',
            'email' => 'dir.keuangan@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($dirKeuanganRole);

        User::create([
            'name' => 'Staf Keuangan',
            'email' => 'staf.keuangan@ummaluku.ac.id',
            'password' => Hash::make('password'),
        ])->assignRole($stafKeuanganRole);
    }
}
