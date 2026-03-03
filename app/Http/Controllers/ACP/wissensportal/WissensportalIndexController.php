<?php

namespace App\Http\Controllers\ACP\Wissensportal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WissensportalIndexController extends Controller
{
    public function wissensportalIndexAction(): View
    {
        return view(view: '/acp/wissensportal/wissensportal-index');
    }
}