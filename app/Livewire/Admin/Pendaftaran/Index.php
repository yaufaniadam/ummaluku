<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination; // <-- 1. Import trait untuk pagination

class Index extends Component
{
    use WithPagination; // <-- 2. Gunakan trait

    // Properti untuk fitur pencarian
    public string $search = '';

    public function render()
    {
        // 3. Ambil data aplikasi pendaftaran, urutkan dari yang terbaru
        $applications = Application::with(['prospective.user', 'batch', 'admissionCategory'])
            ->whereHas('prospective.user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10); // Ambil 10 data per halaman

        return view('livewire.admin.pendaftaran.index', [
            'applications' => $applications,
        ]); // Kita gunakan layout master Tablar
    }
}