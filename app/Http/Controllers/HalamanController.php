<?php

namespace App\Http\Controllers;

class HalamanController extends Controller
{
    public function marketplace()
    {
        return view('pages.marketplace');
    }

    public function isiMarketplace()
    {
        return view('pages.isiMarketplace');
    }

    public function DM()
    {
        return view('pages.dashboardMarketplace');
    }
}