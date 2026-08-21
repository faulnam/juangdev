<?php

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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServicePageController::class, 'index'])->name('services');
Route::get('/portfolio', [PortfolioPageController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{slug}', [PortfolioPageController::class, 'show'])->name('portfolio.show');
Route::get('/blog', [BlogPageController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogPageController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactPageController::class, 'index'])->name('contact');
Route::post('/contact', [ContactPageController::class, 'submit'])->name('contact.submit');

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

        // Site Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
