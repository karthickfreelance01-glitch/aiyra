@extends('layouts.app')
@section('title', 'Projects | ' . ($site['site_name'] ?? 'Aiyra Constructions'))
@section('body_class', 'has-hero')

@section('content')
@include('partials.page-banner', ['title' => 'Our Projects', 'subtitle' => 'A look at homes and spaces we have planned and built.', 'image' => $projects->first()?->cover_url])

<section class="section projects-page">
    <div class="container">
        <div class="filters" data-reveal>
            <div class="filters__group" role="tablist">
                <button class="filter-btn is-active" data-filter="*">All <sup>{{ $projects->count() }}</sup></button>
                @foreach($categories as $c)
                    <button class="filter-btn" data-filter="cat-{{ \Illuminate\Support\Str::slug($c) }}">{{ $c }} <sup>{{ $projects->where('category', $c)->count() }}</sup></button>
                @endforeach
            </div>
            <div class="filters__group filters__group--status">
                @foreach(\App\Models\Project::STATUSES as $k => $label)
                    @if($projects->where('status', $k)->count())
                        <button class="filter-btn filter-btn--sm" data-filter="st-{{ $k }}">{{ $label }}</button>
                    @endif
                @endforeach
            </div>
        </div>

        @if($projects->isEmpty())
            <p class="empty">Projects will be published here soon.</p>
        @endif

        <div class="p-grid" id="pGrid">
            @foreach($projects as $p)
                <a href="{{ route('projects.show', $p) }}" class="p-card cat-{{ \Illuminate\Support\Str::slug($p->category) }} st-{{ $p->status }}" data-cursor="view">
                    <div class="p-card__img">
                        <img src="{{ $p->cover_url }}" alt="{{ $p->title }}" loading="lazy">
                        <span class="status status--{{ $p->status }}">{{ $p->status_label }}</span>
                    </div>
                    <div class="p-card__body">
                        <span class="p-card__cat">{{ $p->category }}</span>
                        <h3>{{ $p->title }}</h3>
                        <div class="p-card__meta">
                            @if($p->location)<span><i class="bi bi-geo-alt"></i>{{ $p->location }}</span>@endif
                            @if($p->area)<span><i class="bi bi-aspect-ratio"></i>{{ $p->area }}</span>@endif
                        </div>
                    </div>
                    <span class="p-card__arrow"><i class="bi bi-arrow-up-right"></i></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta-strip')
@endsection
