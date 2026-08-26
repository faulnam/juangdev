<?php

namespace App\Http\Controllers;

use App\Models\DesignTier;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class EstimatorPageController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::where('is_active', true)->orderBy('display_order')->get();
        $pricingPlans = PricingPlan::where('is_active', true)->orderBy('display_order')->get();
        $serviceFeatures = ServiceFeature::where('is_active', true)->orderBy('display_order')->get();
        $designTiers = DesignTier::orderBy('display_order')->get();
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        $selectedService = $request->query('service');
        $selectedPlan = $request->query('plan');

        return view('pages.estimator', compact(
            'services',
            'pricingPlans',
            'serviceFeatures',
            'designTiers',
            'settings',
            'selectedService',
            'selectedPlan'
        ));
    }
}
