@props(['icon' => 'fa-folder-open', 'title' => '', 'subtitle' => ''])

<div class="pg-header">
    <div class="pg-header-inner">
        <div class="pg-header-icon"><i class="fas {{ $icon }}"></i></div>
        <div>
            <h1 class="pg-header-title">{{ $title }}</h1>
            @if ($subtitle)
                <p class="pg-header-sub">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="pg-header-actions">{{ $actions ?? '' }}</div>
    </div>
</div>