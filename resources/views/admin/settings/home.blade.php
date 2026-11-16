@extends('admin.layouts.app')
@section('title', 'Home Page Settings')
@section('subtitle', 'Edit the hero slider, about block, stats, services, why-choose-us, process, testimonials and call-to-action.')

@php
    $slides = old('hero_slides', setting_json('hero_slides'));
    $stats = old('stats', setting_json('stats'));
    $services = old('services', setting_json('services'));
    $why = old('why_points', setting_json('why_points'));
    $process = old('process', setting_json('process'));
    $testimonials = old('testimonials', setting_json('testimonials'));
    $points = implode("\n", setting_json('home_about_points'));
    $icons = ['bi-buildings','bi-house-heart','bi-rulers','bi-key','bi-hammer','bi-tools','bi-bricks','bi-house-gear','bi-palette','bi-lightning-charge','bi-droplet','bi-tree','bi-shield-check','bi-clipboard-check','bi-truck','bi-paint-bucket'];
@endphp

@section('content')
<form action="{{ route('admin.settings.home.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
    @csrf

    <div class="settings-nav" id="settingsNav">
        <a href="#s-hero" class="is-active">Hero Slider</a>
        <a href="#s-about">About</a>
        <a href="#s-stats">Stats</a>
        <a href="#s-services">Services</a>
        <a href="#s-why">Why Choose Us</a>
        <a href="#s-process">Process</a>
        <a href="#s-testimonials">Testimonials</a>
        <a href="#s-cta">CTA</a>
    </div>

    {{-- HERO --}}
    <div class="card" id="s-hero">
        <div class="card__head"><h3><i class="bi bi-images"></i> Hero Slider</h3><small class="muted">Recommended image size 1920×1080</small></div>
        <div class="card__body">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Badge Text</label><input type="text" name="hero_badge" value="{{ old('hero_badge', $site['hero_badge'] ?? '') }}" class="form-control"></div>
                <div class="form-group"><label class="form-label">Marquee Strip <small>(separate with •)</small></label><input type="text" name="marquee_text" value="{{ old('marquee_text', $site['marquee_text'] ?? '') }}" class="form-control"></div>
            </div>
            <div class="repeater" data-repeater>
                <div class="repeater__items">
                    @foreach($slides as $i => $s)
                        @include('admin.settings.partials.slide-row', ['i' => $i, 's' => $s])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.slide-row', ['i' => '__I__', 's' => []])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="6"><i class="bi bi-plus-lg"></i> Add Slide</button>
            </div>
        </div>
    </div>

    {{-- ABOUT --}}
    <div class="card" id="s-about">
        <div class="card__head"><h3><i class="bi bi-info-square"></i> About Section</h3></div>
        <div class="card__body">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Small Tag</label><input type="text" name="home_about_tag" value="{{ old('home_about_tag', $site['home_about_tag'] ?? '') }}" class="form-control"></div>
                <div class="form-group"><label class="form-label">Years of Experience</label><input type="number" name="experience_years" value="{{ old('experience_years', $site['experience_years'] ?? '') }}" class="form-control"></div>
            </div>
            <div class="form-group"><label class="form-label">Heading</label><input type="text" name="home_about_title" value="{{ old('home_about_title', $site['home_about_title'] ?? '') }}" class="form-control"></div>
            <div class="form-group"><label class="form-label">Text</label><textarea name="home_about_text" rows="4" class="form-control">{{ old('home_about_text', $site['home_about_text'] ?? '') }}</textarea></div>
            <div class="form-group"><label class="form-label">Highlight Points <small>(one per line)</small></label><textarea name="home_about_points" rows="4" class="form-control">{{ old('home_about_points', $points) }}</textarea></div>
            <div class="form-row">
                @include('admin.partials.image-field', ['key' => 'home_about_image', 'label' => 'Main Image'])
                @include('admin.partials.image-field', ['key' => 'home_about_image2', 'label' => 'Small Overlap Image'])
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="card" id="s-stats">
        <div class="card__head"><h3><i class="bi bi-bar-chart"></i> Counters / Stats</h3></div>
        <div class="card__body">
            <div class="repeater" data-repeater>
                <div class="repeater__items repeater__items--grid">
                    @foreach($stats as $i => $s)
                        @include('admin.settings.partials.stat-row', ['i' => $i, 's' => $s])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.stat-row', ['i' => '__I__', 's' => []])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="6"><i class="bi bi-plus-lg"></i> Add Stat</button>
            </div>
        </div>
    </div>

    {{-- SERVICES --}}
    <div class="card" id="s-services">
        <div class="card__head"><h3><i class="bi bi-tools"></i> Services</h3><small class="muted">Also used in the footer, Services page & enquiry dropdown</small></div>
        <div class="card__body">
            <div class="form-group"><label class="form-label">Section Heading</label><input type="text" name="services_title" value="{{ old('services_title', $site['services_title'] ?? '') }}" class="form-control"></div>
            <div class="repeater" data-repeater>
                <div class="repeater__items">
                    @foreach($services as $i => $s)
                        @include('admin.settings.partials.service-row', ['i' => $i, 's' => $s, 'icons' => $icons])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.service-row', ['i' => '__I__', 's' => [], 'icons' => $icons])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="12"><i class="bi bi-plus-lg"></i> Add Service</button>
            </div>
        </div>
    </div>

    {{-- WHY --}}
    <div class="card" id="s-why">
        <div class="card__head"><h3><i class="bi bi-patch-check"></i> Why Choose Us</h3></div>
        <div class="card__body">
            <div class="form-group"><label class="form-label">Heading</label><input type="text" name="why_title" value="{{ old('why_title', $site['why_title'] ?? '') }}" class="form-control"></div>
            <div class="form-group"><label class="form-label">Text</label><textarea name="why_text" rows="2" class="form-control">{{ old('why_text', $site['why_text'] ?? '') }}</textarea></div>
            @include('admin.partials.image-field', ['key' => 'why_image', 'label' => 'Image'])
            <div class="repeater" data-repeater>
                <div class="repeater__items">
                    @foreach($why as $i => $s)
                        @include('admin.settings.partials.pair-row', ['name' => 'why_points', 'i' => $i, 's' => $s])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.pair-row', ['name' => 'why_points', 'i' => '__I__', 's' => []])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="8"><i class="bi bi-plus-lg"></i> Add Point</button>
            </div>
        </div>
    </div>

    {{-- PROCESS --}}
    <div class="card" id="s-process">
        <div class="card__head"><h3><i class="bi bi-diagram-3"></i> Work Process Steps</h3></div>
        <div class="card__body">
            <div class="repeater" data-repeater>
                <div class="repeater__items">
                    @foreach($process as $i => $s)
                        @include('admin.settings.partials.pair-row', ['name' => 'process', 'i' => $i, 's' => $s])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.pair-row', ['name' => 'process', 'i' => '__I__', 's' => []])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="6"><i class="bi bi-plus-lg"></i> Add Step</button>
            </div>
        </div>
    </div>

    {{-- TESTIMONIALS --}}
    <div class="card" id="s-testimonials">
        <div class="card__head"><h3><i class="bi bi-chat-quote"></i> Testimonials</h3></div>
        <div class="card__body">
            <div class="repeater" data-repeater>
                <div class="repeater__items">
                    @foreach($testimonials as $i => $s)
                        @include('admin.settings.partials.testimonial-row', ['i' => $i, 's' => $s])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.testimonial-row', ['i' => '__I__', 's' => []])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="12"><i class="bi bi-plus-lg"></i> Add Testimonial</button>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="card" id="s-cta">
        <div class="card__head"><h3><i class="bi bi-megaphone"></i> Call To Action</h3></div>
        <div class="card__body">
            <div class="form-group"><label class="form-label">Heading</label><input type="text" name="cta_title" value="{{ old('cta_title', $site['cta_title'] ?? '') }}" class="form-control"></div>
            <div class="form-group"><label class="form-label">Text</label><input type="text" name="cta_text" value="{{ old('cta_text', $site['cta_text'] ?? '') }}" class="form-control"></div>
            @include('admin.partials.image-field', ['key' => 'cta_image', 'label' => 'Background Image', 'hint' => '1920×1080'])
        </div>
    </div>

    <div class="save-bar">
        <span class="muted"><i class="bi bi-info-circle"></i> Changes go live on the website immediately after saving.</span>
        <a href="{{ route('home') }}" target="_blank" class="btn btn--light"><i class="bi bi-eye"></i> Preview</a>
        <button type="submit" class="btn btn--primary" data-loading-text="Saving…"><i class="bi bi-check2-circle"></i> Save Home Page</button>
    </div>
</form>
@endsection
