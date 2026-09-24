@props([
    'chart' => [],
])

<div class="col-12 dashboard-chart-side">
    <div class="card custom-card dashboard-week-card">
        <div class="card-header flex-column align-items-stretch">
            <div class="dashboard-chart-heading">
                <div class="card-title mb-0 d-flex align-items-center gap-2">
                    <span class="kpi-card-icon is-success dashboard-week-icon">
                        <i class="bx bxs-first-aid"></i>
                    </span>
                    {{ __('dashboard.charts.specialties_title') }}
                </div>
                <x-ui.period-select data-specialties-period />
            </div>
            <span class="text-muted fs-12 mt-1" data-specialties-subtitle>{{ __('dashboard.charts.specialties_subtitle') }}</span>
        </div>
        <div class="card-body" data-specialties-list>
            @forelse($chart['periods']['month'] ?? [] as $item)
                <div class="dashboard-specialty-row">
                    <span class="dashboard-specialty-name">{{ $item['label'] }}</span>
                    <div class="dashboard-specialty-track">
                        <span class="dashboard-specialty-fill"></span>
                    </div>
                    <span class="dashboard-specialty-count">{{ $item['count'] }}</span>
                </div>
            @empty
                <div class="dashboard-chart-empty">
                    {{ __('dashboard.charts.specialties_empty') }}
                </div>
            @endforelse
        </div>
    </div>
    <script type="application/json" id="dashboard-specialties">
        @json($chart)
    </script>
</div>
