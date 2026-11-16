@extends('admin.layouts.app')
@section('title', 'Enquiries')
@section('subtitle', 'Leads submitted from the website contact form, quote popup and project pages.')

@section('content')
<div class="tabs">
    <a href="{{ route('admin.enquiries.index', request()->except('status', 'page')) }}" class="tabs__item {{ !request('status') ? 'is-active' : '' }}">All <em>{{ $counts['all'] }}</em></a>
    <a href="{{ route('admin.enquiries.index', array_merge(request()->except('page'), ['status' => 'unread'])) }}" class="tabs__item {{ request('status') === 'unread' ? 'is-active' : '' }}">Unread <em class="em-orange">{{ $counts['unread'] }}</em></a>
    <a href="{{ route('admin.enquiries.index', array_merge(request()->except('page'), ['status' => 'read'])) }}" class="tabs__item {{ request('status') === 'read' ? 'is-active' : '' }}">Read <em>{{ $counts['read'] }}</em></a>
</div>

<div class="card">
    <div class="toolbar">
        <form method="GET" class="toolbar__filters">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <div class="input-icon"><i class="bi bi-search"></i><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name, phone, email, message…"></div>
            <button class="btn btn--light">Search</button>
        </form>
        <a href="{{ route('admin.enquiries.export', request()->only('status', 'q')) }}" class="btn btn--light"><i class="bi bi-download"></i> Export CSV</a>
    </div>

    @if($enquiries->isEmpty())
        <div class="empty-state empty-state--lg"><i class="bi bi-inbox"></i><h3>No enquiries</h3><p>New enquiries from the website will appear here.</p></div>
    @else
    <form action="{{ route('admin.enquiries.bulk') }}" method="POST" id="bulkForm">
        @csrf
        <div class="bulk-bar" id="bulkBar">
            <span><b id="bulkCount">0</b> selected</span>
            <button name="action" value="read" class="btn btn--sm btn--light"><i class="bi bi-envelope-open"></i> Mark read</button>
            <button name="action" value="unread" class="btn btn--sm btn--light"><i class="bi bi-envelope"></i> Mark unread</button>
            <button name="action" value="delete" class="btn btn--sm btn--danger" data-confirm-btn="Delete the selected enquiries?"><i class="bi bi-trash3"></i> Delete</button>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-check"><label class="check"><input type="checkbox" id="checkAll"><span></span></label></th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Service</th>
                        <th>Source</th>
                        <th>Received</th>
                        <th class="t-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($enquiries as $e)
                    <tr class="{{ $e->is_read ? '' : 'is-unread' }}">
                        <td><label class="check"><input type="checkbox" name="ids[]" value="{{ $e->id }}" class="row-check"><span></span></label></td>
                        <td>
                            <a href="{{ route('admin.enquiries.show', $e) }}" class="person">
                                <span class="avatar avatar--sm">{{ strtoupper(mb_substr($e->name, 0, 1)) }}</span>
                                <span><strong>{{ $e->name }}</strong>@if($e->message)<small>{{ \Illuminate\Support\Str::limit($e->message, 48) }}</small>@endif</span>
                            </a>
                        </td>
                        <td><a href="tel:{{ $e->phone }}" class="link">{{ $e->phone }}</a>@if($e->email)<br><small class="muted">{{ $e->email }}</small>@endif</td>
                        <td>{{ $e->service ?: '—' }}@if($e->project)<br><small class="muted">{{ $e->project->title }}</small>@endif</td>
                        <td><span class="pill pill--source">{{ ucfirst($e->source) }}</span></td>
                        <td><span title="{{ $e->created_at->format('d M Y, h:i A') }}">{{ $e->created_at->format('d M Y') }}</span><br><small class="muted">{{ $e->created_at->diffForHumans() }}</small></td>
                        <td class="t-right nowrap">
                            <a href="{{ wa_link($e->phone, 'Hi ' . $e->name . ', thank you for contacting Aiyra Constructions.') }}" target="_blank" class="icon-btn icon-btn--sm icon-btn--wa" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                            <a href="{{ route('admin.enquiries.show', $e) }}" class="icon-btn icon-btn--sm" title="View"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </form>
    <div class="pager">{{ $enquiries->links('admin.partials.pagination') }}</div>
    @endif
</div>
@endsection
