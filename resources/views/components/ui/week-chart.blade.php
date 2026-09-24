@props([
    'chart' => [],
])

<div class="col-12 dashboard-chart-week">
    <div class="card custom-card dashboard-week-card">
        <div class="card-header flex-column align-items-stretch">
            <div class="dashboard-chart-heading">
                <div class="card-title mb-0 d-flex align-items-center gap-2">
                    <span class="kpi-card-icon is-info dashboard-week-icon">
                        <i class="bx bxs-calendar"></i>
                    </span>
                    {{ __('dashboard.charts.week_title') }}
                </div>
                <x-ui.period-select value="week" data-week-period />
            </div>
            <span class="text-muted fs-12 mt-1" data-week-subtitle>{{ __('dashboard.charts.week_subtitle') }}</span>
        </div>
        <div class="card-body">
            <div class="dashboard-week-chart">
                <canvas data-chart="week"></canvas>
            </div>
        </div>
    </div>
    <script type="application/json" id="dashboard-week-chart">
        @json($chart)
    </script>
</div>
