@extends('layouts.app')
@section('title', $project->title . ' | ' . ($site['site_name'] ?? 'Aiyra Constructions'))
@section('meta_description', $project->short_description)
@section('body_class', 'has-hero')

@php
    $gallery = collect([$project->cover_url])->merge($project->images->pluck('url'));
    $paras = array_filter(preg_split("/\r?\n\s*\r?\n/", (string) $project->description));
@endphp

@section('content')
@include('partials.page-banner', [
    'title' => $project->title,
    'crumb' => $project->title,
    'parent' => ['url' => route('projects'), 'label' => 'Projects'],
    'subtitle' => $project->category . ' · ' . $project->location,
    'image' => $project->cover_url,
])

<section class="section project-detail">
    <div class="container project-detail__grid">
        <div class="project-detail__main">
            <div class="pd-hero img-reveal" data-lightbox-index="0" data-cursor="zoom">
                <img src="{{ $project->cover_url }}" alt="{{ $project->title }}">
            </div>

            @if($project->images->count())
                <div class="pd-gallery" data-stagger>
                    @foreach($project->images as $i => $img)
                        <button class="pd-thumb" data-lightbox-index="{{ $i + 1 }}" data-cursor="zoom">
                            <img src="{{ $img->url }}" alt="{{ $project->title }} photo {{ $i + 1 }}" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="pd-content">
                <span class="eyebrow" data-reveal>Project Overview</span>
                @if($project->short_description)
                    <p class="lead lead--big" data-reveal>{{ $project->short_description }}</p>
                @endif
                @foreach($paras as $p)
                    <p data-reveal>{!! nl2br(e(trim($p))) !!}</p>
                @endforeach
            </div>
        </div>

        <aside class="project-detail__side">
            <div class="pd-info" data-reveal>
                <h4>Project Details</h4>
                <ul>
                    <li><span>Category</span><strong>{{ $project->category }}</strong></li>
                    <li><span>Status</span><strong><em class="status status--{{ $project->status }}">{{ $project->status_label }}</em></strong></li>
                    @if($project->location)<li><span>Location</span><strong>{{ $project->location }}</strong></li>@endif
                    @if($project->area)<li><span>Built-up Area</span><strong>{{ $project->area }}</strong></li>@endif
                    @if($project->client)<li><span>Client</span><strong>{{ $project->client }}</strong></li>@endif
                    @if($project->year)<li><span>Year</span><strong>{{ $project->year }}</strong></li>@endif
                </ul>
            </div>
            <div class="pd-enquiry" data-reveal>
                <h4>Want a project like this?</h4>
                <p>Tell us about your plot and we'll get back with ideas & an estimate.</p>
                @include('partials.enquiry-form', ['source' => 'project', 'projectId' => $project->id, 'compact' => true])
            </div>
        </aside>
    </div>
</section>

@if($related->count())
<section class="section related">
    <div class="container">
        <div class="section-head section-head--split">
            <div>
                <span class="eyebrow" data-reveal>More Work</span>
                <h2 class="section-title split-words">Related Projects</h2>
            </div>
            <a href="{{ route('projects') }}" class="btn btn--outline magnetic"><span>All Projects</span><i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="p-grid p-grid--3" data-stagger>
            @foreach($related as $p)
                <a href="{{ route('projects.show', $p) }}" class="p-card" data-cursor="view">
                    <div class="p-card__img"><img src="{{ $p->cover_url }}" alt="{{ $p->title }}" loading="lazy"><span class="status status--{{ $p->status }}">{{ $p->status_label }}</span></div>
                    <div class="p-card__body">
                        <span class="p-card__cat">{{ $p->category }}</span>
                        <h3>{{ $p->title }}</h3>
                        <div class="p-card__meta">@if($p->location)<span><i class="bi bi-geo-alt"></i>{{ $p->location }}</span>@endif</div>
                    </div>
                    <span class="p-card__arrow"><i class="bi bi-arrow-up-right"></i></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" aria-hidden="true">
    <button class="lightbox__close" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <button class="lightbox__nav lightbox__prev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
    <img src="" alt="">
    <button class="lightbox__nav lightbox__next" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
    <div class="lightbox__count"></div>
</div>
<script>window.__gallery = @json($gallery->values());</script>
@endsection
