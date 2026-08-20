<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PortfolioPageController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('display_order')->get();
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.portfolio', compact('portfolios', 'settings'));
    }
}
