<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendaftarDashboardController extends Controller
{
    public function showDashboard()
    {
        // Kita beri nama view 'pendaftar.dashboard-page' agar tidak bingung
        // dengan view komponen livewire
        return view('pendaftar.dashboard-page');
    }
}