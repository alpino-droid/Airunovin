<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function club()
    {
        return view('admin.club');
    }

    public function event()
    {
        return view('admin.event');
    }

    public function marketplace()
    {
        return view('admin.marketplace');
    }

    public function registrasi()
    {
        return view('admin.registrasi');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
