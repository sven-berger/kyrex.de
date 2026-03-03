<?php

namespace App\Http\Controllers\ACP;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ACPIndexController extends Controller
{
    public function acpAction(): View
    {
        return view(view: '/acp/acp-index');
    }
}