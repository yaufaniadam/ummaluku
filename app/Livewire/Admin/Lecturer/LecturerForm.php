<?php

namespace App\Livewire\Admin\Lecturer;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LecturerForm extends Component
{
    // Properti baru untuk mode edit
    public ?Lecturer $lecturer = null;
    public ?User $user = null;

    // Properti untuk menampung data dari form
    public $nidn;
    public $fullName;
    public $email;
    public $program_id;
    public $password;
    public $password_confirmation;

    // Properti untuk menampung data master program studi
    public $programs;

    // Method yang dijalankan saat komponen pertama kali di-load
    public function mount(Lecturer $lecturer)
    {
        $this->programs = Program::orderBy('name_id')->get();
        if ($lecturer->exists) {
            $this->lecturer = $lecturer;
            $this->user = $lecturer->user;

            // Isi properti form dengan data yang ada
            $this->nidn = $lecturer->nidn;
            $this->fullName = $lecturer->full_name_with_degree;
            $this->email = $lecturer->user->email;
            $this->program_id = $lecturer->program_id;
        }
    }

    // Aturan validasi yang dinamis
    protected function rules()
    {
        $userId = $this->user?->id;
        $lecturerId = $this->lecturer?->id;

        return [
            'nidn' => ['required', 'numeric', Rule::unique('lecturers', 'nidn')->ignore($lecturerId)],
            'fullName' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'program_id' => 'required|exists:programs,id',
            // Password bersifat opsional saat edit
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    // Method yang dipanggil saat form disubmit
    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                if ($this->lecturer) {
                    // --- LOGIKA UPDATE ---
                    $this->user->update([
                        'name' => $this->fullName,
                        'email' => $this->email,
                    ]);

                    // Update password hanya jika diisi
                    if (!empty($this->password)) {
                        $this->user->update([
                            'password' => Hash::make($this->password),
                        ]);
                    }

                    $this->lecturer->update([
                        'program_id' => $this->program_id,
                        'nidn' => $this->nidn,
                        'full_name_with_degree' => $this->fullName,
                    ]);
                    session()->flash('success', 'Data dosen berhasil diperbarui.');

                } else {
                    // --- LOGIKA CREATE (TETAP SAMA) ---
                    $newUser = User::create([
                        'name' => $this->fullName,
                        'email' => $this->email,
                        'password' => Hash::make($this->password),
                    ]);
                    $newUser->assignRole('Dosen');
                    Lecturer::create([
                        'user_id' => $newUser->id,
                        'program_id' => $this->program_id,
                        'nidn' => $this->nidn,
                        'full_name_with_degree' => $this->fullName,
                    ]);
                    
                    session()->flash('success', 'Data dosen berhasil ditambahkan.');
                }
            });

            return redirect()->route('admin.lecturers.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.lecturer.lecturer-form');
    }
}