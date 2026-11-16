<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Aiyra Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v=1.0">
    @stack('styles')
</head>
<body>
@php
    $menu = [
        ['label' => 'Main'],
        ['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2', 'text' => 'Dashboard', 'active' => 'admin.dashboard'],
        ['label' => 'Content'],
        ['route' => 'admin.projects.index', 'icon' => 'bi-buildings', 'text' => 'Projects', 'active' => 'admin.projects.index|admin.projects.edit'],
        ['route' => 'admin.projects.create', 'icon' => 'bi-cloud-arrow-up', 'text' => 'Upload Project', 'active' => 'admin.projects.create'],
        ['route' => 'admin.enquiries.index', 'icon' => 'bi-envelope-paper', 'text' => 'Enquiries', 'active' => 'admin.enquiries.*', 'badge' => $unreadCount ?? 0],
        ['label' => 'Page Settings'],
        ['route' => 'admin.settings.home', 'icon' => 'bi-house-gear', 'text' => 'Home Page', 'active' => 'admin.settings.home*'],
        ['route' => 'admin.settings.about', 'icon' => 'bi-info-square', 'text' => 'About Us', 'active' => 'admin.settings.about*'],
        ['route' => 'admin.settings.footer', 'icon' => 'bi-layout-text-window-reverse', 'text' => 'Footer & Contact', 'active' => 'admin.settings.footer*'],
        ['label' => 'Account'],
        ['route' => 'admin.account', 'icon' => 'bi-person-gear', 'text' => 'My Account', 'active' => 'admin.account*'],
    ];
@endphp

<div class="admin" id="admin">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/logo-white.png') }}" alt="Aiyra" class="sidebar__logo">
                <img src="{{ asset('assets/images/logo-icon.png') }}" alt="" class="sidebar__icon">
            </a>
        </div>
        <nav class="sidebar__nav">
            @foreach($menu as $m)
                @isset($m['label'])
                    <div class="sidebar__label">{{ $m['label'] }}</div>
                @else
                    @php $isActive = collect(explode('|', $m['active']))->contains(fn ($p) => request()->routeIs($p)); @endphp
                    <a href="{{ route($m['route']) }}" class="sidebar__link {{ $isActive ? 'is-active' : '' }}" data-tip="{{ $m['text'] }}">
                        <i class="bi {{ $m['icon'] }}"></i>
                        <span>{{ $m['text'] }}</span>
                        @if(!empty($m['badge']))<em class="sidebar__badge">{{ $m['badge'] }}</em>@endif
                    </a>
                @endisset
            @endforeach
        </nav>
        <div class="sidebar__foot">
            <a href="{{ route('home') }}" target="_blank" class="sidebar__site"><i class="bi bi-box-arrow-up-right"></i><span>View Website</span></a>
        </div>
    </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- Main --}}
    <div class="main">
        <header class="topbar">
            <button class="icon-btn" id="sidebarToggle" aria-label="Toggle sidebar"><i class="bi bi-layout-sidebar-inset"></i></button>
            <div class="topbar__title">
                <h1>@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')<p>@yield('subtitle')</p>@endif
            </div>
            <div class="topbar__right">
                <a href="{{ route('admin.enquiries.index', ['status' => 'unread']) }}" class="icon-btn" aria-label="Unread enquiries">
                    <i class="bi bi-bell"></i>
                    @if(($unreadCount ?? 0) > 0)<span class="dot-badge">{{ $unreadCount }}</span>@endif
                </a>
                <div class="user-menu" id="userMenu">
                    <button class="user-menu__btn">
                        <span class="avatar">{{ strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                        <span class="user-menu__name">{{ auth()->user()->name ?? 'Admin' }}<small>Administrator</small></span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="user-menu__drop">
                        <a href="{{ route('admin.account') }}"><i class="bi bi-person"></i> My Account</a>
                        <a href="{{ route('home') }}" target="_blank"><i class="bi bi-globe2"></i> View Website</a>
                        <form action="{{ route('admin.logout') }}" method="POST">@csrf<button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            @if(session('success'))
                <div class="alert alert--success" data-autohide><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span><button class="alert__close">&times;</button></div>
            @endif
            @if($errors->any())
                <div class="alert alert--error"><i class="bi bi-exclamation-triangle-fill"></i>
                    <div><strong>Please fix the following:</strong><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    <button class="alert__close">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="admin-foot">© {{ date('Y') }} Aiyra Constructions · Admin Panel</footer>
    </div>
</div>

{{-- Confirm dialog --}}
<div class="confirm" id="confirm" aria-hidden="true">
    <div class="confirm__box">
        <div class="confirm__icon"><i class="bi bi-trash3"></i></div>
        <h3>Are you sure?</h3>
        <p id="confirmText">This action cannot be undone.</p>
        <div class="confirm__actions">
            <button class="btn btn--light" data-confirm-no>Cancel</button>
            <button class="btn btn--danger" data-confirm-yes>Yes, delete</button>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/admin.js') }}?v=1.0"></script>
@stack('scripts')
</body>
</html>
