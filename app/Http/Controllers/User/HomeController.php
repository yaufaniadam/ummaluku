<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Controller ini me-return view 'user.home'
        // yang secara otomatis akan meng-extend 'layouts.app'
        return view('user.home');
    }
}
