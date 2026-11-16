@extends('layouts.app')
@section('title', 'Services | ' . ($site['site_name'] ?? 'Aiyra Constructions'))
@section('body_class', 'has-hero')

@php
    $services = setting_json('services');
    $process = setting_json('process');
@endphp

@section('content')
@include('partials.page-banner', ['title' => 'Our Services', 'subtitle' => 'From the first plan to the final key — everything under one roof.', 'image' => img_url($site['cta_image'] ?? '')])

<section class="section">
    <div class="container">
        <div class="svc-list">
            @foreach($services as $i => $s)
                <article class="svc-row" data-reveal>
                    <span class="svc-row__num">0{{ $i + 1 }}</span>
                    <div class="svc-row__icon"><i class="bi {{ $s['icon'] ?: 'bi-building' }}"></i></div>
                    <div class="svc-row__body">
                        <h3>{{ $s['title'] }}</h3>
                        <p>{{ $s['text'] }}</p>
                    </div>
                    <button class="round-btn round-btn--lg magnetic" data-open-enquiry data-service="{{ $s['title'] }}" aria-label="Enquire about {{ $s['title'] }}"><i class="bi bi-arrow-up-right"></i></button>
                </article>
            @endforeach
        </div>
    </div>
</section>

@if(count($process))
<section class="section process process--dark">
    <div class="container">
        <div class="section-head section-head--center">
            <span class="eyebrow eyebrow--light" data-reveal>How We Work</span>
            <h2 class="section-title section-title--light split-words">From Idea To Handover</h2>
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

@include('partials.cta-strip')
@endsection
