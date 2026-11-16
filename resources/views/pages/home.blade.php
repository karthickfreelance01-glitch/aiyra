@extends('layouts.app')
@section('body_class', 'page-home has-hero')

@php
    $slides = setting_json('hero_slides');
    $stats = setting_json('stats');
    $services = setting_json('services');
    $whyPoints = setting_json('why_points');
    $process = setting_json('process');
    $testimonials = setting_json('testimonials');
    $aboutPoints = setting_json('home_about_points');
    $marquee = array_filter(array_map('trim', preg_split('/[•|,]/u', $site['marquee_text'] ?? '')));
@endphp

@section('content')

{{-- ================= HERO ================= --}}
<section class="hero" id="hero">
    <div class="hero__slides">
        @foreach($slides as $i => $s)
            <div class="hero__slide {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}">
                <div class="hero__img" style="background-image:url('{{ img_url($s['image'] ?? '') }}')"></div>
            </div>
        @endforeach
        <div class="hero__overlay"></div>
        <div class="hero__grid-lines"><span></span><span></span><span></span><span></span></div>
    </div>

    <div class="container hero__content">
        @if(!empty($site['hero_badge']))
            <div class="hero__badge hero-anim"><span class="pulse-dot"></span>{{ $site['hero_badge'] }}</div>
        @endif

        <div class="hero__texts">
            @foreach($slides as $i => $s)
                <div class="hero__text {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}">
                    <h1 class="hero__title">
                        <span class="line"><span class="line__inner">{{ $s['title'] ?? '' }}</span></span>
                        <span class="line"><span class="line__inner text-gradient">{{ $s['highlight'] ?? '' }}</span></span>
                    </h1>
                    <p class="hero__sub"><span class="line"><span class="line__inner">{{ $s['subtitle'] ?? '' }}</span></span></p>
                </div>
            @endforeach
        </div>

        <div class="hero__actions hero-anim">
            <a href="{{ route('projects') }}" class="btn btn--primary btn--lg magnetic"><span>Explore Projects</span><i class="bi bi-arrow-up-right"></i></a>
            <button class="btn btn--ghost btn--lg magnetic" data-open-enquiry><span>Free Consultation</span><i class="bi bi-chat-dots"></i></button>
        </div>
    </div>

    <div class="hero__bottom container">
        <div class="hero__counter">
            <span class="hero__current">01</span>
            <span class="hero__progress"><span></span></span>
            <span class="hero__total">{{ str_pad(count($slides), 2, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="hero__nav">
            <button class="hero__arrow magnetic" data-hero-prev aria-label="Previous"><i class="bi bi-arrow-left"></i></button>
            <button class="hero__arrow magnetic" data-hero-next aria-label="Next"><i class="bi bi-arrow-right"></i></button>
        </div>
        <a href="#about" class="hero__scroll" data-scroll-to><span>Scroll</span><i></i></a>
    </div>
</section>

{{-- ================= ABOUT ================= --}}
<section class="section about-home" id="about">
    <div class="container about-home__grid">
        <div class="about-home__media">
            <div class="img-reveal about-home__img1" data-parallax="-8">
                <img src="{{ img_url($site['home_about_image'] ?? '') }}" alt="About Aiyra Constructions" loading="lazy">
            </div>
            <div class="img-reveal about-home__img2" data-parallax="10">
                <img src="{{ img_url($site['home_about_image2'] ?? '') }}" alt="" loading="lazy">
            </div>
            <div class="exp-badge">
                <svg viewBox="0 0 200 200" class="exp-badge__ring">
                    <defs><path id="circlePath" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"/></defs>
                    <text><textPath href="#circlePath">BUILDING DREAMS • CREATING REALITY • </textPath></text>
                </svg>
                <div class="exp-badge__num"><strong data-count="{{ (int) ($site['experience_years'] ?? 10) }}">0</strong><span>+ Years</span></div>
            </div>
        </div>

        <div class="about-home__content">
            <span class="eyebrow" data-reveal>{{ $site['home_about_tag'] ?? 'About Us' }}</span>
            <h2 class="section-title split-words">{{ $site['home_about_title'] ?? '' }}</h2>
            <p class="lead" data-reveal>{{ $site['home_about_text'] ?? '' }}</p>
            <ul class="check-list" data-stagger>
                @foreach($aboutPoints as $p)
                    <li><i class="bi bi-check-lg"></i>{{ $p }}</li>
                @endforeach
            </ul>
            <div class="about-home__actions" data-reveal>
                <a href="{{ route('about') }}" class="btn btn--dark magnetic"><span>More About Us</span><i class="bi bi-arrow-right"></i></a>
                <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}" class="call-link">
                    <span class="call-link__icon"><i class="bi bi-telephone"></i></span>
                    <span><small>Need help? Call us</small>{{ $site['phone'] ?? '' }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ================= STATS ================= --}}
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

{{-- ================= SERVICES ================= --}}
<section class="section services" id="services">
    <div class="container">
        <div class="section-head section-head--split">
            <div>
                <span class="eyebrow" data-reveal>Our Services</span>
                <h2 class="section-title split-words">{{ $site['services_title'] ?? 'What We Do Best' }}</h2>
            </div>
            <a href="{{ route('services') }}" class="btn btn--outline magnetic" data-reveal><span>All Services</span><i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="services__grid" data-stagger>
            @foreach($services as $i => $s)
                <article class="service-card spotlight tilt">
                    <span class="service-card__num">0{{ $i + 1 }}</span>
                    <div class="service-card__icon"><i class="bi {{ $s['icon'] ?: 'bi-building' }}"></i></div>
                    <h3>{{ $s['title'] }}</h3>
                    <p>{{ $s['text'] }}</p>
                    <button class="service-card__link" data-open-enquiry data-service="{{ $s['title'] }}">Enquire <i class="bi bi-arrow-up-right"></i></button>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= PROJECTS (horizontal scroll) ================= --}}
@if($featured->count())
<section class="h-projects" id="projects">
    <div class="h-projects__pin">
        <div class="container h-projects__head">
            <div>
                <span class="eyebrow eyebrow--light">Featured Work</span>
                <h2 class="section-title section-title--light">Projects We're <span class="text-gradient">Proud Of</span></h2>
            </div>
            <a href="{{ route('projects') }}" class="btn btn--light magnetic"><span>View All Projects</span><i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="h-projects__track">
            @foreach($featured as $i => $p)
                <a href="{{ route('projects.show', $p) }}" class="h-card" data-cursor="view">
                    <div class="h-card__img"><img src="{{ $p->cover_url }}" alt="{{ $p->title }}" loading="lazy"></div>
                    <div class="h-card__info">
                        <span class="h-card__cat">{{ $p->category }} · <em class="status status--{{ $p->status }}">{{ $p->status_label }}</em></span>
                        <h3>{{ $p->title }}</h3>
                        <p><i class="bi bi-geo-alt"></i> {{ $p->location }} @if($p->area)&nbsp;·&nbsp; {{ $p->area }}@endif</p>
                    </div>
                    <span class="h-card__index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </a>
            @endforeach
            <a href="{{ route('projects') }}" class="h-card h-card--more" data-cursor="hover">
                <span><i class="bi bi-arrow-up-right"></i></span>
                <strong>See all<br>projects</strong>
            </a>
        </div>
        <div class="h-projects__progress container"><span></span></div>
    </div>
</section>
@endif

{{-- ================= WHY CHOOSE US ================= --}}
<section class="section why">
    <div class="container why__grid">
        <div class="why__media">
            <div class="img-reveal why__img"><img src="{{ img_url($site['why_image'] ?? '') }}" alt="Why choose Aiyra" loading="lazy" data-parallax-img></div>
            <div class="why__float glass">
                <i class="bi bi-award"></i>
                <div><strong>Quality Assured</strong><span>Every stage inspected</span></div>
            </div>
        </div>
        <div class="why__content">
            <span class="eyebrow" data-reveal>Why Choose Us</span>
            <h2 class="section-title split-words">{{ $site['why_title'] ?? '' }}</h2>
            <p class="lead" data-reveal>{{ $site['why_text'] ?? '' }}</p>
            <div class="why__list" data-stagger>
                @foreach($whyPoints as $i => $w)
                    <div class="why__item">
                        <span class="why__icon">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div><h4>{{ $w['title'] }}</h4><p>{{ $w['text'] }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ================= PROCESS ================= --}}
@if(count($process))
<section class="section process">
    <div class="container">
        <div class="section-head section-head--center">
            <span class="eyebrow" data-reveal>How We Work</span>
            <h2 class="section-title split-words">Our Simple 4-Step Process</h2>
        </div>
        <div class="process__line"><span class="process__fill"></span></div>
        <div class="process__grid" data-stagger>
            @foreach($process as $i => $p)
                <div class="process__step">
                    <div class="process__dot"><span>{{ $i + 1 }}</span></div>
                    <h4>{{ $p['title'] }}</h4>
                    <p>{{ $p['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= TESTIMONIALS ================= --}}
@if(count($testimonials))
<section class="section testimonials">
    <div class="container testimonials__grid">
        <div>
            <span class="eyebrow" data-reveal>Testimonials</span>
            <h2 class="section-title split-words">What Our Clients Say</h2>
            <div class="testimonials__nav" data-reveal>
                <button class="round-btn magnetic" data-t-prev aria-label="Previous"><i class="bi bi-arrow-left"></i></button>
                <button class="round-btn magnetic" data-t-next aria-label="Next"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
        <div class="t-slider" data-reveal>
            <i class="bi bi-quote t-slider__quote"></i>
            @foreach($testimonials as $i => $t)
                <figure class="t-slide {{ $i === 0 ? 'is-active' : '' }}">
                    <div class="t-stars">@for($s = 0; $s < 5; $s++)<i class="bi bi-star-fill"></i>@endfor</div>
                    <blockquote>{{ $t['text'] }}</blockquote>
                    <figcaption><span class="t-avatar">{{ mb_substr($t['name'], 0, 1) }}</span><div><strong>{{ $t['name'] }}</strong><small>{{ $t['role'] }}</small></div></figcaption>
                </figure>
            @endforeach
            <div class="t-dots">@foreach($testimonials as $i => $t)<span class="{{ $i === 0 ? 'is-active' : '' }}"></span>@endforeach</div>
        </div>
    </div>
</section>
@endif

{{-- ================= CTA ================= --}}
<section class="cta">
    <div class="cta__bg" style="background-image:url('{{ img_url($site['cta_image'] ?? '') }}')" data-parallax-bg></div>
    <div class="cta__overlay"></div>
    <div class="container cta__inner">
        <h2 class="split-words">{{ $site['cta_title'] ?? '' }}</h2>
        <p data-reveal>{{ $site['cta_text'] ?? '' }}</p>
        <div class="cta__actions" data-reveal>
            <button class="btn btn--primary btn--lg magnetic" data-open-enquiry><span>Get Free Estimate</span><i class="bi bi-arrow-up-right"></i></button>
            <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}" class="btn btn--ghost btn--lg magnetic"><span>{{ $site['phone'] ?? '' }}</span><i class="bi bi-telephone"></i></a>
        </div>
    </div>
</section>

@endsection
