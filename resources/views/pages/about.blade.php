@extends('layouts.app')
@section('title', 'About Us | ' . ($site['site_name'] ?? 'Aiyra Constructions'))
@section('body_class', 'has-hero')

@php
    $values = setting_json('about_values');
    $stats = setting_json('stats');
    $paras = array_filter(preg_split("/\r?\n\s*\r?\n/", $site['about_content'] ?? ''));
@endphp

@section('content')
@include('partials.page-banner', ['title' => 'About Us', 'subtitle' => $site['tagline'] ?? null, 'image' => img_url($site['about_banner'] ?? '')])

<section class="section about-page">
    <div class="container about-home__grid">
        <div class="about-home__media">
            <div class="img-reveal about-home__img1" data-parallax="-8"><img src="{{ img_url($site['about_image'] ?? '') }}" alt="About Aiyra" loading="lazy"></div>
            <div class="img-reveal about-home__img2" data-parallax="10"><img src="{{ img_url($site['about_image2'] ?? '') }}" alt="" loading="lazy"></div>
            <div class="exp-badge">
                <svg viewBox="0 0 200 200" class="exp-badge__ring">
                    <defs><path id="circlePath2" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"/></defs>
                    <text><textPath href="#circlePath2">BUILDING DREAMS • CREATING REALITY • </textPath></text>
                </svg>
                <div class="exp-badge__num"><strong data-count="{{ (int) ($site['experience_years'] ?? 10) }}">0</strong><span>+ Years</span></div>
            </div>
        </div>
        <div class="about-home__content">
            <span class="eyebrow" data-reveal>{{ $site['about_tag'] ?? 'Who We Are' }}</span>
            <h2 class="section-title split-words">{{ $site['about_title'] ?? '' }}</h2>
            @foreach($paras as $p)
                <p class="lead" data-reveal>{!! nl2br(e(trim($p))) !!}</p>
            @endforeach
        </div>
    </div>
</section>

<section class="section mv">
    <div class="container mv__grid" data-stagger>
        <div class="mv-card spotlight">
            <div class="mv-card__icon"><i class="bi bi-bullseye"></i></div>
            <h3>Our Mission</h3>
            <p>{{ $site['mission'] ?? '' }}</p>
        </div>
        <div class="mv-card mv-card--accent spotlight">
            <div class="mv-card__icon"><i class="bi bi-eye"></i></div>
            <h3>Our Vision</h3>
            <p>{{ $site['vision'] ?? '' }}</p>
        </div>
    </div>
</section>

@if(count($stats))
<section class="stats">
    <div class="container stats__grid" data-stagger>
        @foreach($stats as $st)
            <div class="stat">
                <div class="stat__num"><span data-count="{{ (int) $st['number'] }}">0</span><em>{{ $st['suffix'] }}</em></div>
                <div class="stat__label">{{ $st['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>
@endif

@if(count($values))
<section class="section values">
    <div class="container">
        <div class="section-head section-head--center">
            <span class="eyebrow" data-reveal>Our Core Values</span>
            <h2 class="section-title split-words">The Foundation Of Everything We Build</h2>
        </div>
        <div class="values__grid" data-stagger>
            @foreach($values as $v)
                <div class="value-card spotlight tilt">
                    <div class="value-card__icon"><i class="bi {{ $v['icon'] ?: 'bi-star' }}"></i></div>
                    <h4>{{ $v['title'] }}</h4>
                    <p>{{ $v['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($site['founder_message']))
<section class="section founder">
    <div class="container founder__inner">
        @if(!empty($site['founder_image']))
            <div class="founder__img img-reveal"><img src="{{ img_url($site['founder_image']) }}" alt="{{ $site['founder_name'] ?? '' }}" loading="lazy"></div>
        @endif
        <div class="founder__text">
            <i class="bi bi-quote founder__quote"></i>
            <blockquote class="split-words">{{ $site['founder_message'] }}</blockquote>
            <div class="founder__name" data-reveal><strong>{{ $site['founder_name'] ?? '' }}</strong><span>{{ $site['founder_role'] ?? '' }}</span></div>
        </div>
    </div>
</section>
@endif

@include('partials.cta-strip')
@endsection
