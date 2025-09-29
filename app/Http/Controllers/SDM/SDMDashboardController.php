<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\SDMDashboardService; // <-- Tambahkan ini
use Illuminate\Http\Request;

class SDMDashboardController extends Controller
{
    public function index(SDMDashboardService $service)
    {
        $data = $service->getDashboardData();
        return view('dashboard.sdm', $data);
    }
}