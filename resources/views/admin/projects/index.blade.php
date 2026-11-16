@extends('admin.layouts.app')
@section('title', 'Projects')
@section('subtitle', 'All projects shown on the website.')

@section('content')
<div class="card">
    <div class="toolbar">
        <form method="GET" class="toolbar__filters">
            <div class="input-icon"><i class="bi bi-search"></i><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search title or location…"></div>
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All status</option>
                @foreach(\App\Models\Project::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
            </select>
            <select name="category" class="form-control" onchange="this.form.submit()">
                <option value="">All categories</option>
                @foreach(\App\Models\Project::CATEGORIES as $c)<option value="{{ $c }}" @selected(request('category') === $c)>{{ $c }}</option>@endforeach
            </select>
            @if(request()->hasAny(['q', 'status', 'category']))<a href="{{ route('admin.projects.index') }}" class="btn btn--light">Reset</a>@endif
        </form>
        <a href="{{ route('admin.projects.create') }}" class="btn btn--primary"><i class="bi bi-cloud-arrow-up"></i> Upload Project</a>
    </div>

    @if($projects->isEmpty())
        <div class="empty-state empty-state--lg">
            <i class="bi bi-buildings"></i>
            <h3>No projects found</h3>
            <p>Upload your first project to show it on the website.</p>
            <a href="{{ route('admin.projects.create') }}" class="btn btn--primary"><i class="bi bi-plus-lg"></i> Upload Project</a>
        </div>
    @else
        <div class="project-cards">
            @foreach($projects as $p)
                <div class="project-card">
                    <a href="{{ route('admin.projects.edit', $p) }}" class="project-card__img">
                        <img src="{{ $p->cover_url }}" alt="{{ $p->title }}" loading="lazy">
                        <span class="pill pill--{{ $p->status }}">{{ $p->status_label }}</span>
                        @if($p->images_count)<span class="project-card__count"><i class="bi bi-images"></i> {{ $p->images_count + 1 }}</span>@endif
                    </a>
                    <div class="project-card__body">
                        <small class="project-card__cat">{{ $p->category }}</small>
                        <h4><a href="{{ route('admin.projects.edit', $p) }}">{{ $p->title }}</a></h4>
                        <p class="muted"><i class="bi bi-geo-alt"></i> {{ $p->location ?: '—' }}</p>
                        <div class="project-card__toggles">
                            <label class="switch" title="Show on website">
                                <input type="checkbox" data-toggle-url="{{ route('admin.projects.toggle', $p) }}" data-field="is_active" @checked($p->is_active)><span></span> Active
                            </label>
                            <label class="switch" title="Show on home page">
                                <input type="checkbox" data-toggle-url="{{ route('admin.projects.toggle', $p) }}" data-field="is_featured" @checked($p->is_featured)><span></span> Featured
                            </label>
                        </div>
                    </div>
                    <div class="project-card__actions">
                        <a href="{{ route('projects.show', $p) }}" target="_blank" class="icon-btn icon-btn--sm" title="View on site"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.projects.edit', $p) }}" class="icon-btn icon-btn--sm" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.projects.destroy', $p) }}" method="POST" data-confirm="Delete project “{{ $p->title }}” and all its images?">
                            @csrf @method('DELETE')
                            <button class="icon-btn icon-btn--sm icon-btn--danger" title="Delete"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="pager">{{ $projects->links('admin.partials.pagination') }}</div>
    @endif
</div>
@endsection
