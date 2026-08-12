{{-- Paginasi custom, desain disamakan dengan menu evaluasi --}}
@if ($paginator->hasPages())
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-top:8px;">
        <div style="font-size:12px;color:#6b7280;">
            Menampilkan <b>{{ $paginator->total() }}</b> data &middot;
            Halaman <b>{{ $paginator->currentPage() }}/{{ $paginator->lastPage() }}</b>
        </div>

        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#374151;opacity:0.4;cursor:not-allowed;">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#374151;text-decoration:none;">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;color:#cbd5e1;">&hellip;</span>
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #7664E4;background:#7664E4;color:#fff;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#374151;text-decoration:none;">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#374151;text-decoration:none;">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#374151;opacity:0.4;cursor:not-allowed;">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif