@props([
    'title',
    'value' => '-',
    'subtitle' => '',
    'change' => null,
    'changeIcon' => 'fe fe-arrow-up-circle',
    'changeClass' => 'text-success',
    'chartId' => null,
    'icon' => null,
    'tone' => 'info',
    'color' => '#3b82f6',
    'series' => [],
    'trend' => null,
    'columnClass' => 'col-lg-6 col-md-6 col-sm-12 col-xxl-3',
])

@php
    $chartId ??= Str::slug($title);

    [$changeIcon, $changeClass] = match ($trend) {
        'up' => ['fe fe-arrow-up-circle', 'text-success'],
        'down' => ['fe fe-arrow-down-circle', 'text-danger'],
        'flat' => ['fe fe-minus-circle', 'text-muted'],
        default => [null, null],
    };

    if ($trend === 'flat') {
        $change = null;
    }
@endphp

<div class="{{ $columnClass }}">
    <div class="card overflow-hidden">
        <div class="card-body">
            <div class="d-flex">
                @if($icon)
                    <div class="kpi-card-icon is-{{ $tone }} me-2 mt-1">
                        <i class="bx {{ $icon }}"></i>
                    </div>
                @endif
                <div class="mt-2">
                    <h6 class="fw-normal">{{ $title }}</h6>
                    <h2 class="mb-0 text-dark fw-semibold">{{ $value }}</h2>
                </div>
                <div class="ms-auto">
                    <div class="chart-wrapper mt-1">
                        <canvas
                            id="{{ $chartId }}-chart"
                            class="chart-dropshadow"
                            width="96"
                            height="64"
                            style="width: 6rem; height: 4rem;"
                            data-series='@json($series)'
                            data-color="{{ $color }}"
                        ></canvas>
                    </div>
                </div>
            </div>
            <span class="text-muted fs-12 kpi-card-trend">
                @if($change)
                    <span class="{{ $changeClass }}">
                        <i class="{{ $changeIcon }} {{ $changeClass }}"></i> {{ $change }}
                    </span>
                @elseif($trend === 'flat')
                    <span class="{{ $changeClass }}">
                        <i class="{{ $changeIcon }} {{ $changeClass }}"></i>
                    </span>
                @endif
                {{ $subtitle }}
            </span>
        </div>
    </div>
</div>
