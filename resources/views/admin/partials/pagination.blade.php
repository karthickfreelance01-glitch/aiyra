@if ($paginator->hasPages())
<nav class="pagination" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="pagination__item is-disabled"><i class="bi bi-chevron-left"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination__item"><i class="bi bi-chevron-left"></i></a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pagination__item is-disabled">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pagination__item is-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination__item">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination__item"><i class="bi bi-chevron-right"></i></a>
    @else
        <span class="pagination__item is-disabled"><i class="bi bi-chevron-right"></i></span>
    @endif
    <span class="pagination__info">Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</span>
</nav>
@endif
