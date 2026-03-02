@if($results->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted small" id="paginationInfo">
            Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} results
        </div>
        <nav aria-label="Quiz results pagination">
            <ul class="pagination mb-0">
                {{-- Previous Page Link --}}
                @if($results->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="loadPage({{ $results->currentPage() - 1 }})" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $start = max(1, $results->currentPage() - 2);
                    $end = min($results->lastPage(), $results->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="loadPage(1)">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $results->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" onclick="loadPage({{ $page }})">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                @if($end < $results->lastPage())
                    @if($end < $results->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="loadPage({{ $results->lastPage() }})">{{ $results->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if($results->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="loadPage({{ $results->currentPage() + 1 }})" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
