<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function homeAction(): View
    {
        return view('home');
    }
}
