<nav class="pagination-wrap">
    <div class="pagination-info" style="color:var(--text-muted); font-size:12.5px;">
        Showing <strong style="color:var(--text-dark);">{{ $paginator->firstItem() ?? ($paginator->total() > 0 ? 1 : 0) }}</strong> to <strong style="color:var(--text-dark);">{{ $paginator->lastItem() ?? $paginator->total() }}</strong> of <strong style="color:var(--text-dark);">{{ number_format($paginator->total()) }}</strong> entries
    </div>

    <div class="pagination-links">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-btn">Previous</a>
        @endif

        {{-- Pagination Elements --}}
        @if ($paginator->hasPages())
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="padding:6px 6px; font-size:12px; color:var(--text-muted);">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @else
            <span class="page-btn active">1</span>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-btn">Next</a>
        @else
            <span class="page-btn disabled">Next</span>
        @endif
    </div>
</nav>
