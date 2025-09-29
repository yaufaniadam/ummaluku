<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\PMBDashboardService; // <-- Tambahkan ini
use Illuminate\Http\Request;

class PMBDashboardController extends Controller
{
    public function index(PMBDashboardService $service)
    {
        $data = $service->getDashboardData();
        return view('dashboard.pmb', $data);
    }
}