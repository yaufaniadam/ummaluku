<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin', 'Dosen']);
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // Super Admin dan Admin bisa lihat semua
        if ($user->hasAnyRole(['Super Admin', 'Admin'])) {
            return true;
        }

        // Mahasiswa hanya bisa lihat data sendiri
        if ($user->hasRole('Mahasiswa')) {
            return $user->id === $student->user_id;
        }

        // Dosen Wali bisa lihat mahasiswa bimbingannya
        if ($user->hasRole('Dosen') && $user->lecturer) {
            return $student->academic_advisor_id === $user->lecturer->id;
        }

        return false;
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        // Only Super Admin and Admin can update student records
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        // Only Super Admin can delete students
        return $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can generate KRS for the student.
     */
    public function generateKrs(User $user, Student $student): bool
    {
        // Super Admin and Admin can generate for any student
        if ($user->hasAnyRole(['Super Admin', 'Admin'])) {
            return true;
        }

        // Mahasiswa bisa generate KRS sendiri
        if ($user->hasRole('Mahasiswa')) {
            return $user->id === $student->user_id;
        }

        return false;
    }
}
