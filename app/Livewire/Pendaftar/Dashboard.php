<?php

namespace App\Livewire\Pendaftar;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public Application $application;
    public $requiredDocuments;

    public function mount()
    {
        $user = Auth::user();
        $this->application = Application::whereHas('prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with([
            'admissionCategory.documentRequirements',
            'documents',
            'batch', // <-- Tambahkan ini untuk mengambil data gelombang
            // 'admissionCategory' sudah otomatis terambil sebagian karena relasi di atas,
            // tapi tidak ada salahnya menambahkannya secara eksplisit untuk kejelasan.
            'admissionCategory' 
        ])->firstOrFail();

        $this->requiredDocuments = $this->application->admissionCategory->documentRequirements;
    }

    public function render()
    {
        return view('livewire.pendaftar.dashboard')->layout('layouts.frontend');;
    }
}