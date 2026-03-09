<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GatewayController extends Controller
{
    /**
     * Display the gateway page with portal options.
     */
    public function index(): View
    {
        return view('gateway');
    }
}
