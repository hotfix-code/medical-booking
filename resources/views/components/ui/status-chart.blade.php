@props([
    'chart' => [],
])

<div class="col-12 dashboard-chart-side">
    <div class="card custom-card dashboard-week-card">
        <div class="card-header flex-column align-items-stretch">
            <div class="dashboard-chart-heading">
                <div class="card-title mb-0 d-flex align-items-center gap-2">
                    <span class="kpi-card-icon is-warning dashboard-week-icon">
                        <i class="bx bxs-pie-chart-alt-2"></i>
                    </span>
                    {{ __('dashboard.charts.status_title') }}
                </div>
                <x-ui.period-select data-status-period />
            </div>
            <span class="text-muted fs-12 mt-1" data-status-subtitle>{{ __('dashboard.charts.status_subtitle') }}</span>
        </div>
        <div class="card-body">
            <div class="dashboard-status-body">
                <div class="dashboard-status-chart">
                    <canvas data-chart="status"></canvas>
                    <div class="dashboard-status-center">
                        <span class="dashboard-status-total" data-status-total>{{ $chart['periods']['month']['total'] ?? 0 }}</span>
                        <span class="dashboard-status-unit" data-status-unit>{{ $chart['periods']['month']['unit'] ?? '' }}</span>
                    </div>
                </div>
                <ul class="dashboard-status-legend list-unstyled mb-0" data-status-legend>
                    @foreach($chart['periods']['month']['legend'] ?? [] as $item)
                        <li>
                            <span class="dashboard-status-legend-label">
                                <span class="dashboard-status-dot" style="background: {{ $item['color'] }}"></span>
                                {{ $item['label'] }}
                            </span>
                            <span class="dashboard-status-legend-value">
                                {{ $item['value'] }}
                                <span class="text-muted">({{ $item['percent'] }}%)</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <script type="application/json" id="dashboard-status-chart">
        @json($chart)
    </script>
</div>
