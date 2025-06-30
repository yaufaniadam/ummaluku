<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Notifications\MahasiswaDiterima; 

class NotifController extends Controller
{
   

    public function testWhatsApp()
    {
        
       new MahasiswaDiterima('');
        
    }
  
}