@extends('layouts.app')
@section('title', 'Contact Us | ' . ($site['site_name'] ?? 'Aiyra Constructions'))
@section('body_class', 'has-hero')

@section('content')
@include('partials.page-banner', ['title' => 'Contact Us', 'subtitle' => "Let's talk about your next project.", 'image' => img_url($site['home_about_image'] ?? '')])

<section class="section contact">
    <div class="container contact__grid">
        <div class="contact__info">
            <span class="eyebrow" data-reveal>Get In Touch</span>
            <h2 class="section-title split-words">We'd Love To Hear About Your Dream Project</h2>
            <div class="contact__cards" data-stagger>
                @if(!empty($site['phone']))
                <a href="tel:{{ preg_replace('/\s+/', '', $site['phone']) }}" class="c-card spotlight">
                    <span class="c-card__icon"><i class="bi bi-telephone"></i></span>
                    <div><small>Call Us</small><strong>{{ $site['phone'] }}</strong>@if(!empty($site['phone2']))<strong>{{ $site['phone2'] }}</strong>@endif</div>
                </a>
                @endif
                @if(!empty($site['email']))
                <a href="mailto:{{ $site['email'] }}" class="c-card spotlight">
                    <span class="c-card__icon"><i class="bi bi-envelope"></i></span>
                    <div><small>Email Us</small><strong>{{ $site['email'] }}</strong></div>
                </a>
                @endif
                @if(!empty($site['address']))
                <div class="c-card spotlight">
                    <span class="c-card__icon"><i class="bi bi-geo-alt"></i></span>
                    <div><small>Visit Us</small><strong>{{ $site['address'] }}</strong></div>
                </div>
                @endif
                @if(!empty($site['working_hours']))
                <div class="c-card spotlight">
                    <span class="c-card__icon"><i class="bi bi-clock"></i></span>
                    <div><small>Working Hours</small><strong>{{ $site['working_hours'] }}</strong></div>
                </div>
                @endif
            </div>
        </div>

        <div class="contact__form" data-reveal>
            <h3>Send Us An Enquiry</h3>
            <p>Fill the form and our team will reach you within 24 hours.</p>
            @include('partials.enquiry-form', ['source' => 'contact'])
        </div>
    </div>
</section>

@if(!empty($site['map_embed']))
<section class="map" data-reveal>
    <iframe src="{{ $site['map_embed'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Location map" allowfullscreen></iframe>
</section>
@endif
@endsection
