@if ($paginator->hasPages())
    <nav class="portal-pagination">
        @if ($paginator->onFirstPage())
            <span class="kit-button secondary disabled">Previous</span>
        @else
            <a class="kit-button secondary" href="{{ $paginator->previousPageUrl() }}">Previous</a>
        @endif

        <span class="portal-pagination-status">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a class="kit-button secondary" href="{{ $paginator->nextPageUrl() }}">Next</a>
        @else
            <span class="kit-button secondary disabled">Next</span>
        @endif
    </nav>
@endif
