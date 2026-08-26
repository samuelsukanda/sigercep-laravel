{{-- Paginasi custom, desain disamakan dengan menu evaluasi --}}
@if ($paginator->hasPages())
    <div class="kb-pagination-wrap">
        <div class="kb-pagination-info">
            Menampilkan <b>{{ $paginator->total() }}</b> data &middot;
            Halaman <b>{{ $paginator->currentPage() }}/{{ $paginator->lastPage() }}</b>
        </div>

        <div class="kb-pagination-btns">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="kb-page-btn disabled">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="kb-page-btn">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="kb-page-ellipsis">&hellip;</span>
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="kb-page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="kb-page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="kb-page-btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="kb-page-btn disabled">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif
