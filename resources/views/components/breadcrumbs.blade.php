@props([
    'title' => 'Page Title',
    'subtitle' => null,
])

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        {{-- Left side --}}
        <div>
            <h3 class="mb-0">{{ $title }}</h3>

            @if ($subtitle)
                <p class="text-muted mb-0">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Right side (optional actions) --}}
        @if (isset($actions))
            <div class="d-flex align-items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
