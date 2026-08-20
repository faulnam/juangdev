<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\DesignTier;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('display_order')->get();
        $pricingPlans = PricingPlan::where('is_active', true)->orderBy('display_order')->get();
        $portfolios = Portfolio::orderBy('display_order')->get();
        $testimonials = Testimonial::orderBy('display_order')->get();
        $blogs = Blog::where('is_published', true)->orderBy('published_at', 'desc')->take(3)->get();
        $serviceFeatures = ServiceFeature::where('is_active', true)->orderBy('display_order')->get();
        $designTiers = DesignTier::orderBy('display_order')->get();
        
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.home', compact(
            'services',
            'pricingPlans',
            'portfolios',
            'testimonials',
            'blogs',
            'serviceFeatures',
            'designTiers',
            'settings'
        ));
    }
}
