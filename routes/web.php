<?php

use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\Admin\ShowcaseController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceFeatureController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BlogPageController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\EstimatorPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

use App\Http\Controllers\SitemapController;
use App\Http\Controllers\LlmsTxtController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServicePageController::class, 'index'])->name('services');
Route::get('/estimator', [EstimatorPageController::class, 'index'])->name('estimator');
Route::get('/estimasi-biaya', [EstimatorPageController::class, 'index']);
Route::get('/portfolio', [PortfolioPageController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{slug}', [PortfolioPageController::class, 'show'])->name('portfolio.show');
Route::get('/blog', [BlogPageController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogPageController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactPageController::class, 'index'])->name('contact');
Route::post('/contact', [ContactPageController::class, 'submit'])->name('contact.submit');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/llms.txt', [LlmsTxtController::class, 'index'])->name('llms');
Route::get('/llms-full.txt', [LlmsTxtController::class, 'full'])->name('llms.full');

use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerPortalController;

/*
|--------------------------------------------------------------------------
| Customer Authentication & Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
Route::post('/auth/google-firebase', [CustomerAuthController::class, 'firebaseGoogleAuth'])->name('auth.google-firebase');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [CustomerPortalController::class, 'orders'])->name('orders');
    Route::get('/orders/{invoiceNumber}', [CustomerPortalController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{invoiceNumber}/testimonial', [CustomerPortalController::class, 'submitTestimonial'])->name('orders.testimonial');
    Route::get('/profile', [CustomerPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');
});

// Public Invoice & Pakasir Order Routes
Route::post('/orders', [InvoiceController::class, 'store'])->name('orders.store');
Route::get('/orders/{invoiceNumber}/check-status', [InvoiceController::class, 'checkStatus'])->name('orders.check-status');
Route::get('/invoice/{invoiceNumber}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::post('/invoice/{invoiceNumber}/pay', [InvoiceController::class, 'pay'])->name('invoice.pay');
Route::post('/webhook/pakasir', [InvoiceController::class, 'webhook'])->name('webhook.pakasir');

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/chat/status', [ChatController::class, 'status'])->name('api.chat.status');
    Route::post('/chat', [ChatController::class, 'chat'])->name('api.chat');
    Route::post('/upload', [UploadController::class, 'upload'])->name('api.upload');
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Protected Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Orders & Invoices Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/send-wa', [AdminOrderController::class, 'sendWaReminder'])->name('orders.send-wa');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        // Customers Management
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{phone}', [AdminCustomerController::class, 'show'])->name('customers.show');

        // Contacts Management
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::patch('/contacts/{contact}', [ContactController::class, 'updateStatus'])->name('contacts.status');
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

        // Services & Addons
        Route::resource('services', AdminServiceController::class);
        Route::resource('service-features', ServiceFeatureController::class)->except(['create', 'edit', 'show']);

        // Pricing Plans
        Route::resource('pricing', PricingController::class);

        // Portfolios
        Route::resource('portfolios', AdminPortfolioController::class);

        // Testimonials
        Route::resource('testimonials', TestimonialController::class)->except(['create', 'edit', 'show']);

        // Blogs
        Route::resource('blogs', BlogController::class);

        // Showcase Layanan
        Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
        Route::post('/showcase', [ShowcaseController::class, 'update'])->name('showcase.update');

        // FAQ Management
        Route::resource('faqs', FaqController::class)->except(['show']);

        // Cara Pemesanan (Process Steps)
        Route::resource('process-steps', ProcessStepController::class)->except(['show']);

        // Hero Sections Management (All Pages)
        Route::get('/hero-sections', [HeroSectionController::class, 'index'])->name('hero-sections.index');
        Route::post('/hero-sections', [HeroSectionController::class, 'update'])->name('hero-sections.update');

        // About Section Management (Tentang Kami Bento Grid)
        Route::get('/about-section', [AboutSectionController::class, 'index'])->name('about-section.index');
        Route::post('/about-section', [AboutSectionController::class, 'update'])->name('about-section.update');

        // Site Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
