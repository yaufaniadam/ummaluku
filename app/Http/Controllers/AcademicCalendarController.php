<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        return view('pages.academic-calendar');
    }
}