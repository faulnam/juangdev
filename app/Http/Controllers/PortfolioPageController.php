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

    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)->firstOrFail();
        $relatedPortfolios = Portfolio::where('id', '!=', $portfolio->id)
            ->orderBy('display_order')
            ->take(3)
            ->get();
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.portfolio-detail', compact('portfolio', 'relatedPortfolios', 'settings'));
    }
}
