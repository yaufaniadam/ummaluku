<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AkademikDashboardService; 
use Illuminate\Http\Request;

class AkademikDashboardController extends Controller
{
    public function index(AkademikDashboardService $service)
    {
        $data = $service->getDashboardData();
        return view('dashboard.akademik', $data);
    }
}