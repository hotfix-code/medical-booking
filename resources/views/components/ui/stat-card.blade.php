@props([
    'title',
    'value' => '-',
    'subtitle' => '',
    'change' => null,
    'changeIcon' => 'fe fe-arrow-up-circle',
    'changeClass' => 'text-success',
    'chartId' => null,
])

<div class="col-lg-6 col-md-6 col-sm-12 col-xxl-3">
    <div class="card overflow-hidden">
        <div class="card-body">
            <div class="d-flex">
                <div class="mt-2">
                    <h6 class="fw-normal">{{ $title }}</h6>
                    <h2 class="mb-0 text-dark fw-semibold">{{ $value }}</h2>
                </div>
                <div class="ms-auto">
                    <div class="chart-wrapper mt-1">
                        <canvas
                            id="{{ $chartId ?? Str::slug($title) }}-chart"
                            class="chart-dropshadow"
                            style="width: 6rem; height: 4rem;"
                        ></canvas>
                    </div>
                </div>
            </div>
            <span class="text-muted fs-12">
                @if($change)
                    <span class="{{ $changeClass }}">
                        <i class="{{ $changeIcon }} {{ $changeClass }}"></i> {{ $change }}
                    </span>
                @endif
                {{ $subtitle }}
            </span>
        </div>
    </div>
</div>
