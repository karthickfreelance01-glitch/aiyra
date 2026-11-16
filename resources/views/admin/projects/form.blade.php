@extends('admin.layouts.app')
@php $editing = $project->exists; @endphp
@section('title', $editing ? 'Edit Project' : 'Upload Project')
@section('subtitle', $editing ? $project->title : 'Add a new project with cover image and gallery.')

@section('content')
<form action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="form-layout">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="form-layout__main">
        <div class="card">
            <div class="card__head"><h3><i class="bi bi-card-text"></i> Project Information</h3></div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Project Title <b>*</b></label>
                    <input type="text" name="title" value="{{ old('title', $project->title) }}" class="form-control" required maxlength="190" placeholder="e.g. Modern Villa Residence">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category <b>*</b></label>
                        <input type="text" name="category" value="{{ old('category', $project->category ?: 'Residential') }}" class="form-control" list="catList" required>
                        <datalist id="catList">@foreach(\App\Models\Project::CATEGORIES as $c)<option value="{{ $c }}">@endforeach</datalist>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <b>*</b></label>
                        <div class="seg">
                            @foreach(\App\Models\Project::STATUSES as $k => $v)
                                <label><input type="radio" name="status" value="{{ $k }}" @checked(old('status', $project->status) === $k)><span>{{ $v }}</span></label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-row form-row--4">
                    <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" value="{{ old('location', $project->location) }}" class="form-control" placeholder="Pollachi"></div>
                    <div class="form-group"><label class="form-label">Built-up Area</label><input type="text" name="area" value="{{ old('area', $project->area) }}" class="form-control" placeholder="2,400 sq.ft"></div>
                    <div class="form-group"><label class="form-label">Client</label><input type="text" name="client" value="{{ old('client', $project->client) }}" class="form-control" placeholder="Optional"></div>
                    <div class="form-group"><label class="form-label">Year</label><input type="text" name="year" value="{{ old('year', $project->year) }}" class="form-control" placeholder="{{ date('Y') }}"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description <small>(shown on cards, max 500)</small></label>
                    <textarea name="short_description" class="form-control" rows="2" maxlength="500" data-counter>{{ old('short_description', $project->short_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Description <small>(leave a blank line between paragraphs)</small></label>
                    <textarea name="description" class="form-control" rows="8">{{ old('description', $project->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card__head"><h3><i class="bi bi-images"></i> Gallery Images</h3><small class="muted">JPG, PNG, WEBP · max 5 MB each</small></div>
            <div class="card__body">
                @if($editing && $project->images->count())
                    <div class="gallery-grid">
                        @foreach($project->images as $img)
                            <div class="gallery-item" id="gimg-{{ $img->id }}">
                                <img src="{{ $img->url }}" alt="">
                                <button type="button" class="gallery-item__del" data-delete-image="{{ route('admin.projects.images.destroy', [$project, $img]) }}" title="Remove"><i class="bi bi-x-lg"></i></button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <label class="dropzone" data-dropzone>
                    <input type="file" name="gallery[]" accept="image/jpeg,image/png,image/webp" multiple>
                    <i class="bi bi-cloud-arrow-up"></i>
                    <strong>Drop images here or <u>browse</u></strong>
                    <span>You can select multiple images at once</span>
                </label>
                <div class="gallery-grid gallery-grid--preview" data-dropzone-preview></div>
            </div>
        </div>
    </div>

    <aside class="form-layout__side">
        <div class="card sticky">
            <div class="card__head"><h3><i class="bi bi-image"></i> Cover Image @unless($editing)<b>*</b>@endunless</h3></div>
            <div class="card__body">
                <label class="image-picker {{ $editing && $project->cover_image ? 'has-image' : '' }}">
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" {{ $editing ? '' : 'required' }} data-preview>
                    <img src="{{ $editing ? $project->cover_url : '' }}" alt="">
                    <div class="image-picker__empty"><i class="bi bi-image"></i><span>Click to upload cover</span><small>Recommended 1200×900</small></div>
                    <span class="image-picker__change"><i class="bi bi-arrow-repeat"></i> Change</span>
                </label>

                <div class="divider"></div>
                <label class="switch switch--row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $project->is_active ?? true))><span></span> Show on website</label>
                <label class="switch switch--row"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))><span></span> Feature on home page</label>
                <div class="form-group">
                    <label class="form-label">Display Order <small>(lower shows first)</small></label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" class="form-control">
                </div>

                <button type="submit" class="btn btn--primary btn--block btn--lg" data-loading-text="Uploading…">
                    <i class="bi bi-{{ $editing ? 'check2-circle' : 'cloud-arrow-up' }}"></i> {{ $editing ? 'Save Changes' : 'Upload Project' }}
                </button>
                @if($editing)
                    <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn btn--light btn--block"><i class="bi bi-eye"></i> View on website</a>
                @endif
                <a href="{{ route('admin.projects.index') }}" class="btn btn--ghost btn--block">Cancel</a>
            </div>
        </div>
    </aside>
</form>
@endsection
