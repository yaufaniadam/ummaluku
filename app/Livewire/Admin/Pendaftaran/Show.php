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
        // Kita load relasi yang dibutuhkan agar tidak terjadi N+1 problem
        $this->application->load(['prospective.user', 'batch', 'admissionCategory', 'programChoices.program']);
    }

    public function render()
    {
        return view('livewire.admin.pendaftaran.show');
    }
}