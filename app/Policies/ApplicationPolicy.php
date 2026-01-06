<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Determine if the user can view any applications.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view applications');
    }

    /**
     * Determine if the user can view the application.
     */
    public function view(User $user, Application $application): bool
    {
        // Admin/PMB Staff bisa lihat semua
        if ($user->hasPermission('view applications')) {
            return true;
        }

        // Camaru hanya bisa lihat aplikasi sendiri
        if ($user->hasRole('Camaru') && $user->prospective) {
            return $application->prospective_id === $user->prospective->id;
        }

        return false;
    }

    /**
     * Determine if the user can create applications.
     */
    public function create(User $user): bool
    {
        // Camaru bisa create aplikasi
        // Atau admin bisa create atas nama orang lain
        return $user->hasAnyRole(['Camaru', 'Super Admin', 'Admin']);
    }

    /**
     * Determine if the user can update the application.
     */
    public function update(User $user, Application $application): bool
    {
        // Admin bisa update semua
        if ($user->hasPermission('manage pmb settings')) {
            return true;
        }

        // Camaru hanya bisa update aplikasi sendiri yang masih draft/pending
        if ($user->hasRole('Camaru') && $user->prospective) {
            return $application->prospective_id === $user->prospective->id &&
                   in_array($application->status, ['draft', 'pending']);
        }

        return false;
    }

    /**
     * Determine if the user can delete the application.
     */
    public function delete(User $user, Application $application): bool
    {
        // Only Super Admin can delete applications
        return $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can finalize the application.
     */
    public function finalize(User $user, Application $application): bool
    {
        // Only users with PMB management permission can finalize
        return $user->hasPermission('manage pmb settings') || 
               $user->hasPermission('view applications');
    }

    /**
     * Determine if the user can accept/reject the application.
     */
    public function acceptOrReject(User $user, Application $application): bool
    {
        // Only users with PMB management permission
        return $user->hasPermission('manage pmb settings');
    }
}
