@extends('layouts.app')

@section('title', 'JuangDev — Jasa Pembuatan Website & Custom Software')
@section('meta_description', 'JuangDev membantu bisnis, startup, dan UMKM membangun website profesional, aplikasi web, toko online, dan sistem kustom berkualitas tinggi.')

@section('content')
    <!-- 1. Hero Section -->
    @include('partials.hero')

    <!-- 2. About Section Bento Grid -->
    @include('partials.about')

    <!-- 3. Services Section -->
    @include('partials.services')

    <!-- 4. Projects Showcase (Portfolio) -->
    @include('partials.portfolio')

    <!-- 5. 4 Easy Steps To Start Your Project (Process) -->
    @include('partials.process')

    <!-- 6. Paket & Investasi Terbaik Untuk Bisnis Anda (Pricing) -->
    @include('partials.pricing')

    <!-- 7. What Our Clients Say (Testimonials Continuous Loop) -->
    @include('partials.testimonials')

    <!-- 8. Ada yang Ingin Ditanyakan? (FAQ) -->
    @include('partials.faq')

    <!-- 9. Ready to Build Your Next Digital Product? (Final CTA) -->
    @include('partials.final-cta')

    <!-- 10. Price Estimator A La Carte -->
    @include('partials.estimator')
@endsection
