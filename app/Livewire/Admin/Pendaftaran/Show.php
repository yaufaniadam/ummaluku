<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\Application;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{
    // Properti ini akan diisi secara otomatis oleh Livewire
    // berkat Route Model Binding
    public Application $application;

    public function mount(Application $application)
    {
        // Kita tambahkan relasi baru untuk dimuat di sini
        $this->application->load([
            'prospective.user',
            'batch',
            'admissionCategory.documentRequirements', // <-- Ambil syarat dokumen dari kategori
            'programChoices.program',
            'documents' // <-- Ambil dokumen yang sudah di-upload oleh pendaftar
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.show');
    }
}
