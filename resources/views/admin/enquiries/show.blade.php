@extends('admin.layouts.app')
@section('title', 'Enquiry Details')
@section('subtitle', 'Received ' . $enquiry->created_at->format('d M Y, h:i A'))

@section('content')
<div class="form-layout">
    <div class="form-layout__main">
        <div class="card">
            <div class="enquiry-head">
                <span class="avatar avatar--lg">{{ strtoupper(mb_substr($enquiry->name, 0, 1)) }}</span>
                <div>
                    <h2>{{ $enquiry->name }}</h2>
                    <p class="muted">{{ $enquiry->service ?: 'General enquiry' }} · via {{ ucfirst($enquiry->source) }} form</p>
                </div>
                <span class="pill {{ $enquiry->is_read ? 'pill--completed' : 'pill--ongoing' }}">{{ $enquiry->is_read ? 'Read' : 'Unread' }}</span>
            </div>
            <div class="card__body">
                <div class="detail-grid">
                    <div><small>Phone</small><a href="tel:{{ $enquiry->phone }}" class="link">{{ $enquiry->phone }}</a></div>
                    <div><small>Email</small>@if($enquiry->email)<a href="mailto:{{ $enquiry->email }}" class="link">{{ $enquiry->email }}</a>@else — @endif</div>
                    <div><small>Service</small>{{ $enquiry->service ?: '—' }}</div>
                    <div><small>Site Location</small>{{ $enquiry->location ?: '—' }}</div>
                    @if($enquiry->project)
                        <div><small>Project Page</small><a href="{{ route('projects.show', $enquiry->project) }}" target="_blank" class="link">{{ $enquiry->project->title }}</a></div>
                    @endif
                    <div><small>IP Address</small>{{ $enquiry->ip ?: '—' }}</div>
                </div>
                <div class="message-box">
                    <small>Message</small>
                    <p>{!! $enquiry->message ? nl2br(e($enquiry->message)) : '<em class="muted">No message provided.</em>' !!}</p>
                </div>
            </div>
        </div>
    </div>
    <aside class="form-layout__side">
        <div class="card">
            <div class="card__head"><h3><i class="bi bi-lightning-charge"></i> Quick Actions</h3></div>
            <div class="card__body stack">
                <a href="tel:{{ $enquiry->phone }}" class="btn btn--primary btn--block"><i class="bi bi-telephone"></i> Call {{ $enquiry->phone }}</a>
                <a href="{{ wa_link($enquiry->phone, 'Hi ' . $enquiry->name . ', thank you for contacting Aiyra Constructions.') }}" target="_blank" class="btn btn--wa btn--block"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                @if($enquiry->email)
                    <a href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: Your enquiry with Aiyra Constructions') }}" class="btn btn--light btn--block"><i class="bi bi-envelope"></i> Reply by Email</a>
                @endif
                <form action="{{ route('admin.enquiries.toggle', $enquiry) }}" method="POST">@csrf
                    <button class="btn btn--light btn--block"><i class="bi bi-envelope{{ $enquiry->is_read ? '' : '-open' }}"></i> Mark as {{ $enquiry->is_read ? 'unread' : 'read' }}</button>
                </form>
                <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" data-confirm="Delete this enquiry from {{ $enquiry->name }}?">@csrf @method('DELETE')
                    <button class="btn btn--danger-soft btn--block"><i class="bi bi-trash3"></i> Delete Enquiry</button>
                </form>
                <a href="{{ route('admin.enquiries.index') }}" class="btn btn--ghost btn--block"><i class="bi bi-arrow-left"></i> Back to list</a>
            </div>
        </div>
    </aside>
</div>
@endsection
