@extends('admin.layouts.app')
@section('title', 'About Us Page')
@section('subtitle', 'Edit the content of the About Us page.')

@php
    $values = old('about_values', setting_json('about_values'));
    $icons = ['bi-shield-check','bi-gem','bi-clock-history','bi-people','bi-award','bi-heart','bi-lightbulb','bi-hand-thumbs-up','bi-star','bi-graph-up-arrow','bi-tree','bi-buildings'];
@endphp

@section('content')
<form action="{{ route('admin.settings.about.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
    @csrf

    <div class="card">
        <div class="card__head"><h3><i class="bi bi-image"></i> Page Banner</h3></div>
        <div class="card__body">
            @include('admin.partials.image-field', ['key' => 'about_banner', 'label' => 'Banner Background', 'hint' => '1920×800 · also used as default banner'])
        </div>
    </div>

    <div class="card">
        <div class="card__head"><h3><i class="bi bi-file-text"></i> Company Story</h3></div>
        <div class="card__body">
            <div class="form-row form-row--1-2">
                <div class="form-group"><label class="form-label">Small Tag</label><input type="text" name="about_tag" value="{{ old('about_tag', $site['about_tag'] ?? '') }}" class="form-control"></div>
                <div class="form-group"><label class="form-label">Heading <b>*</b></label><input type="text" name="about_title" value="{{ old('about_title', $site['about_title'] ?? '') }}" class="form-control" required></div>
            </div>
            <div class="form-group">
                <label class="form-label">Content <small>(leave a blank line between paragraphs)</small></label>
                <textarea name="about_content" rows="9" class="form-control">{{ old('about_content', $site['about_content'] ?? '') }}</textarea>
            </div>
            <div class="form-row">
                @include('admin.partials.image-field', ['key' => 'about_image', 'label' => 'Main Image'])
                @include('admin.partials.image-field', ['key' => 'about_image2', 'label' => 'Small Overlap Image'])
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__head"><h3><i class="bi bi-bullseye"></i> Mission & Vision</h3></div>
        <div class="card__body">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Mission</label><textarea name="mission" rows="4" class="form-control">{{ old('mission', $site['mission'] ?? '') }}</textarea></div>
                <div class="form-group"><label class="form-label">Vision</label><textarea name="vision" rows="4" class="form-control">{{ old('vision', $site['vision'] ?? '') }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__head"><h3><i class="bi bi-gem"></i> Core Values</h3></div>
        <div class="card__body">
            <div class="repeater" data-repeater>
                <div class="repeater__items">
                    @foreach($values as $i => $s)
                        @include('admin.settings.partials.value-row', ['i' => $i, 's' => $s, 'icons' => $icons])
                    @endforeach
                </div>
                <template>@include('admin.settings.partials.value-row', ['i' => '__I__', 's' => [], 'icons' => $icons])</template>
                <button type="button" class="btn btn--dashed" data-add-row data-max="8"><i class="bi bi-plus-lg"></i> Add Value</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__head"><h3><i class="bi bi-person-badge"></i> Founder / MD Message</h3><small class="muted">Leave the message empty to hide this section</small></div>
        <div class="card__body">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Name</label><input type="text" name="founder_name" value="{{ old('founder_name', $site['founder_name'] ?? '') }}" class="form-control"></div>
                <div class="form-group"><label class="form-label">Designation</label><input type="text" name="founder_role" value="{{ old('founder_role', $site['founder_role'] ?? '') }}" class="form-control"></div>
            </div>
            <div class="form-group"><label class="form-label">Message</label><textarea name="founder_message" rows="3" class="form-control">{{ old('founder_message', $site['founder_message'] ?? '') }}</textarea></div>
            @include('admin.partials.image-field', ['key' => 'founder_image', 'label' => 'Photo', 'hint' => 'optional · portrait 800×1000'])
        </div>
    </div>

    <div class="save-bar">
        <span class="muted"><i class="bi bi-info-circle"></i> Stats on the About page come from Home Page → Stats.</span>
        <a href="{{ route('about') }}" target="_blank" class="btn btn--light"><i class="bi bi-eye"></i> Preview</a>
        <button type="submit" class="btn btn--primary" data-loading-text="Saving…"><i class="bi bi-check2-circle"></i> Save About Page</button>
    </div>
</form>
@endsection
