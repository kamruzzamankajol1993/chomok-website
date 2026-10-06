@if ($paginator->total() > 0)
@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $pageItems = [];

    if ($lastPage <= 7) {
        for ($page = 1; $page <= $lastPage; $page++) {
            $pageItems[] = $page;
        }
    } else {
        $pageItems[] = 1;

        if ($currentPage <= 4) {
            $startPage = 2;
            $endPage = 5;
        } elseif ($currentPage >= $lastPage - 3) {
            $startPage = $lastPage - 4;
            $endPage = $lastPage - 1;
        } else {
            $startPage = $currentPage - 1;
            $endPage = $currentPage + 1;
        }

        if ($startPage > 2) {
            $pageItems[] = '...';
        }

        for ($page = $startPage; $page <= $endPage; $page++) {
            $pageItems[] = $page;
        }

        if ($endPage < $lastPage - 1) {
            $pageItems[] = '...';
        }

        $pageItems[] = $lastPage;
    }
@endphp
<div class="admin-pagination ajax-pagination">
    <span>Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} records</span>
    @if ($paginator->hasPages())
        <div class="pagination-btns">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled"><i class="bi bi-chevron-left"></i></span>
            @else
                <a class="pagination-btn" href="{{ $paginator->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
            @endif

            @foreach ($pageItems as $pageItem)
                @if ($pageItem === '...')
                    <span class="pagination-btn disabled">...</span>
                @elseif ($pageItem == $currentPage)
                    <span class="pagination-btn active">{{ $pageItem }}</span>
                @else
                    <a class="pagination-btn" href="{{ $paginator->url($pageItem) }}">{{ $pageItem }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-btn" href="{{ $paginator->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="pagination-btn disabled"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    @endif
</div>
@endif
