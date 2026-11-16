<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($site['site_name'] ?? 'Aiyra Constructions') . ' | ' . ($site['tagline'] ?? 'Building Dreams | Creating Reality'))</title>
    <meta name="description" content="@yield('meta_description', 'Aiyra Constructions, Pollachi — building construction, residential projects, architectural planning and turnkey solutions.')">
    <script>document.documentElement.classList.add('js');try{if(sessionStorage.getItem('aiyra-seen')==='1')document.documentElement.classList.add('no-preloader');}catch(e){}setTimeout(function(){if(!window.__aiyraReady){document.documentElement.classList.add('reduced');var p=document.getElementById('preloader');if(p)p.remove();}},5000);</script>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=1.5">
    @stack('styles')
</head>
<body class="@yield('body_class', 'has-hero')">

    {{-- Preloader --}}
    <div class="preloader" id="preloader">
        <div class="preloader__inner">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="" class="preloader__logo">
            <div class="preloader__bar"><span></span></div>
        </div>
        <div class="preloader__panel preloader__panel--1"></div>
        <div class="preloader__panel preloader__panel--2"></div>
    </div>

    @include('partials.header')

    <main id="smooth-content">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.enquiry-modal')

    {{-- Floating actions --}}
    @if(!empty($site['whatsapp']))
    <a href="{{ wa_link($site['whatsapp'], 'Hi Aiyra Constructions, I would like to know more about your services.') }}" target="_blank" rel="noopener" class="wa-float" aria-label="Chat on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
    @endif
    <button class="to-top" id="toTop" aria-label="Back to top">
        <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="22"/></svg>
        <i class="bi bi-arrow-up"></i>
    </button>

    <div class="toast" id="toast"></div>

    @if(session('success'))
        <script>window.__flash = @json(session('success'));</script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.13/dist/lenis.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}?v=1.5"></script>
    @stack('scripts')
</body>
</html>
