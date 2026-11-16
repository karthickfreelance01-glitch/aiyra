@php
    $nav = [
        ['route' => 'home', 'label' => 'Home'],
        ['route' => 'about', 'label' => 'About Us'],
        ['route' => 'services', 'label' => 'Services'],
        ['route' => 'projects', 'label' => 'Projects'],
        ['route' => 'contact', 'label' => 'Contact'],
    ];
@endphp

{{-- Top bar --}}
<div class="topbar">
    <div class="container topbar__inner">
        <div class="topbar__left">
            @if(!empty($site['address']))<span><i class="bi bi-geo-alt-fill"></i> {{ $site['address'] }}</span>@endif
            @if(!empty($site['working_hours']))<span class="hide-md"><i class="bi bi-clock-fill"></i> {{ $site['working_hours'] }}</span>@endif
        </div>
        <div class="topbar__right">
            @if(!empty($site['email']))<a href="mailto:{{ $site['email'] }}"><i class="bi bi-envelope-fill"></i> {{ $site['email'] }}</a>@endif
            <div class="topbar__social">
                @foreach(['facebook' => 'facebook', 'instagram' => 'instagram', 'youtube' => 'youtube', 'linkedin' => 'linkedin'] as $k => $icon)
                    @if(!empty($site[$k]))<a href="{{ $site[$k] }}" target="_blank" rel="noopener" aria-label="{{ $k }}"><i class="bi bi-{{ $icon }}"></i></a>@endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<header class="header" id="header">
    <div class="container header__inner">
        <a href="{{ route('home') }}" class="header__logo" data-cursor="hover">
            <img src="{{ asset('assets/images/logo.png') }}" alt="{{ $site['site_name'] ?? 'Aiyra Constructions' }}" class="logo-dark">
            <img src="{{ asset('assets/images/logo-white.png') }}" alt="" class="logo-light" aria-hidden="true">
        </a>

        <nav class="nav" aria-label="Main">
            <ul>
                @foreach($nav as $item)
                    <li><a href="{{ route($item['route']) }}" class="nav__link {{ request()->routeIs($item['route'] . '*') ? 'active' : '' }}"><span class="nav__roll"><span data-text="{{ $item['label'] }}">{{ $item['label'] }}</span></span></a></li>
                @endforeach
            </ul>
        </nav>

        <div class="header__actions">
            <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}" class="header__call" data-cursor="hover">
                <span class="header__call-icon"><i class="bi bi-telephone-fill"></i></span>
                <span class="header__call-text"><small>Call Us Now</small>{{ $site['phone'] ?? '' }}</span>
            </a>
            <button class="btn btn--primary btn--sm magnetic" data-open-enquiry><span>Get a Quote</span><i class="bi bi-arrow-up-right"></i></button>
            <button class="burger" id="burger" aria-label="Menu"><span></span><span></span><span></span></button>
        </div>
    </div>
</header>

{{-- Fullscreen mobile menu --}}
<div class="menu-overlay" id="menuOverlay">
    <div class="menu-overlay__bg"></div>
    <div class="menu-overlay__inner container">
        <ul class="menu-overlay__nav">
            @foreach($nav as $i => $item)
                <li><a href="{{ route($item['route']) }}"><small>0{{ $i + 1 }}</small>{{ $item['label'] }}</a></li>
            @endforeach
        </ul>
        <div class="menu-overlay__info">
            <p><i class="bi bi-telephone"></i> {{ $site['phone'] ?? '' }}</p>
            <p><i class="bi bi-envelope"></i> {{ $site['email'] ?? '' }}</p>
            <p><i class="bi bi-geo-alt"></i> {{ $site['address'] ?? '' }}</p>
        </div>
    </div>
</div>
