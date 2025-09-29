<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\KeuanganDashboardService; // <-- Tambahkan ini
use Illuminate\Http\Request;

class KeuanganDashboardController extends Controller
{
    public function index(KeuanganDashboardService $service)
    {
        $data = $service->getDashboardData();
        return view('dashboard.keuangan', $data);
    }
}