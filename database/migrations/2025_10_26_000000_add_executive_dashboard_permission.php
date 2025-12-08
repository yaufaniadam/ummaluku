<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the permission if it doesn't exist
        $permissionName = 'view-executive-dashboard';

        if (!Permission::where('name', $permissionName)->exists()) {
            $permission = Permission::create(['name' => $permissionName]);
        } else {
            $permission = Permission::where('name', $permissionName)->first();
        }

        // 2. Assign to 'Eksekutif' Role
        $roleName = 'Eksekutif';
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $role->givePermissionTo($permission);
        }

        // 3. Assign to 'Super Admin' (optional, usually Super Admin has all via Gate, but good for explicitness)
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            // Usually Super Admin bypasses, but just in case
            // $superAdmin->givePermissionTo($permission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't delete permission in down() as it might be used elsewhere or manual rollback is preferred
        // to avoid accidental data loss during dev.
        // But for strict reversibility:
        // $role = Role::where('name', 'Eksekutif')->first();
        // if($role) $role->revokePermissionTo('view-executive-dashboard');
        // Permission::where('name', 'view-executive-dashboard')->delete();
    }
};
