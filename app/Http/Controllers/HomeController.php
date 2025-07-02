<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\AdmissionCategory;
use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
        $categories = AdmissionCategory::where('is_active', true)->get();
        $programs = Program::get();
        $activeBatch = Batch::where('is_active', true)->first();

        return view('user.home', [
            'categories' => $categories,
            'activeBatch' => $activeBatch,
            'prodis' => $programs,
        ]);
    }
}
