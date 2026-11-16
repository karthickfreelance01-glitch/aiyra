@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . (auth()->user()->name ?? 'Admin') . ' — here is what is happening today.')

@section('content')
<div class="stat-grid">
    <a href="{{ route('admin.projects.index') }}" class="stat-card stat-card--blue">
        <div class="stat-card__icon"><i class="bi bi-buildings"></i></div>
        <div><span>Total Projects</span><strong data-count="{{ $stats['projects'] }}">{{ $stats['projects'] }}</strong>
            <small>{{ $stats['completed'] }} completed · {{ $stats['ongoing'] }} ongoing</small></div>
    </a>
    <a href="{{ route('admin.enquiries.index') }}" class="stat-card stat-card--orange">
        <div class="stat-card__icon"><i class="bi bi-envelope-paper"></i></div>
        <div><span>Total Enquiries</span><strong data-count="{{ $stats['enquiries'] }}">{{ $stats['enquiries'] }}</strong>
            <small>All time leads</small></div>
    </a>
    <a href="{{ route('admin.enquiries.index', ['status' => 'unread']) }}" class="stat-card stat-card--red">
        <div class="stat-card__icon"><i class="bi bi-bell"></i></div>
        <div><span>Unread Enquiries</span><strong data-count="{{ $stats['unread'] }}">{{ $stats['unread'] }}</strong>
            <small>Need a response</small></div>
    </a>
    <div class="stat-card stat-card--green">
        <div class="stat-card__icon"><i class="bi bi-calendar-check"></i></div>
        <div><span>Today's Enquiries</span><strong data-count="{{ $stats['today'] }}">{{ $stats['today'] }}</strong>
            <small>{{ now()->format('d M Y') }}</small></div>
    </div>
</div>

<div class="grid-2-1">
    <div class="card">
        <div class="card__head"><h3><i class="bi bi-graph-up-arrow"></i> Enquiries – last 6 months</h3></div>
        <div class="card__body"><div class="chart-wrap"><canvas id="enqChart"></canvas></div></div>
    </div>
    <div class="card">
        <div class="card__head"><h3><i class="bi bi-pie-chart"></i> Projects by category</h3></div>
        <div class="card__body">
            @forelse($byCategory as $cat => $total)
                <div class="bar-row">
                    <div class="bar-row__top"><span>{{ $cat }}</span><strong>{{ $total }}</strong></div>
                    <div class="bar-row__track"><span style="--w: {{ $stats['projects'] ? round($total / $stats['projects'] * 100) : 0 }}%"></span></div>
                </div>
            @empty
                <p class="muted">No projects yet.</p>
            @endforelse
            <div class="quick-actions">
                <a href="{{ route('admin.projects.create') }}" class="btn btn--primary btn--block"><i class="bi bi-cloud-arrow-up"></i> Upload New Project</a>
            </div>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card__head">
            <h3><i class="bi bi-envelope-paper"></i> Recent Enquiries</h3>
            <a href="{{ route('admin.enquiries.index') }}" class="link">View all <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="list">
            @forelse($recentEnquiries as $e)
                <a href="{{ route('admin.enquiries.show', $e) }}" class="list__item {{ $e->is_read ? '' : 'is-unread' }}">
                    <span class="avatar avatar--sm">{{ strtoupper(mb_substr($e->name, 0, 1)) }}</span>
                    <div class="list__main"><strong>{{ $e->name }}</strong><small>{{ $e->service ?: 'General enquiry' }} · {{ $e->phone }}</small></div>
                    <span class="list__time">{{ $e->created_at->diffForHumans(null, true) }}</span>
                </a>
            @empty
                <div class="empty-state"><i class="bi bi-inbox"></i><p>No enquiries yet.</p></div>
            @endforelse
        </div>
    </div>
    <div class="card">
        <div class="card__head">
            <h3><i class="bi bi-buildings"></i> Latest Projects</h3>
            <a href="{{ route('admin.projects.index') }}" class="link">Manage <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="list">
            @forelse($recentProjects as $p)
                <a href="{{ route('admin.projects.edit', $p) }}" class="list__item">
                    <img src="{{ $p->cover_url }}" alt="" class="list__thumb">
                    <div class="list__main"><strong>{{ $p->title }}</strong><small>{{ $p->category }} · {{ $p->location }}</small></div>
                    <span class="pill pill--{{ $p->status }}">{{ $p->status_label }}</span>
                </a>
            @empty
                <div class="empty-state"><i class="bi bi-buildings"></i><p>No projects yet.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const el = document.getElementById('enqChart');
    if (!el || typeof Chart === 'undefined') return;
    const ctx = el.getContext('2d');
    const g = ctx.createLinearGradient(0, 0, 0, 260);
    g.addColorStop(0, 'rgba(243,101,35,.35)');
    g.addColorStop(1, 'rgba(243,101,35,0)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chart['labels']),
            datasets: [{ data: @json($chart['data']), borderColor: '#f36523', backgroundColor: g, fill: true, tension: .4, borderWidth: 3, pointRadius: 5, pointBackgroundColor: '#fff', pointBorderColor: '#f36523', pointBorderWidth: 2, pointHoverRadius: 7 }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#061a30', padding: 12, cornerRadius: 10, displayColors: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#8a94a3' } },
                y: { beginAtZero: true, ticks: { precision: 0, color: '#8a94a3' }, grid: { color: 'rgba(6,26,48,.06)' }, border: { display: false } }
            },
            animation: { duration: 1400, easing: 'easeOutQuart' }
        }
    });
})();
</script>
@endpush
