<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpvController extends Controller
{
    public function index()
    {
        return view('spv.dashboard');
    }
}
