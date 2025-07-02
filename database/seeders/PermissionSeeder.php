<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- PMB MODULE PERMISSIONS ---
        Permission::create(['name' => 'view pmb']);
        Permission::create(['name' => 'manage pmb']);

        // --- AKADEMIK MODULE PERMISSIONS ---
        Permission::create(['name' => 'view akademik']);
        Permission::create(['name' => 'manage akademik']);

        // --- SDM MODULE PERMISSIONS ---
        Permission::create(['name' => 'view sdm']);
        Permission::create(['name' => 'manage sdm']);
        
        // --- EXECUTIVE PERMISSIONS ---
        Permission::create(['name' => 'view executive dashboard']);

        // --- SETTINGS PERMISSIONS ---
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage settings']);
    }
}