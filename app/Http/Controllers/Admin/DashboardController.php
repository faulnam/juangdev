<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'contacts_total' => Contact::count(),
            'contacts_unread' => Contact::where('status', 'unread')->count(),
            'services_count' => Service::count(),
            'portfolios_count' => Portfolio::count(),
            'testimonials_count' => Testimonial::count(),
            'pricing_plans_count' => PricingPlan::count(),
            'blogs_count' => Blog::count(),
        ];

        $recentContacts = Contact::orderBy('created_at', 'desc')->take(6)->get();
        $popularServices = Service::where('popular', true)->get();

        return view('admin.dashboard', compact('stats', 'recentContacts', 'popularServices'));
    }
}
