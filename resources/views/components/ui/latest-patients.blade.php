@props([
    'items' => [],
])

<div class="col-12 dashboard-chart-side">
    <div class="card custom-card dashboard-week-card">
        <div class="card-header flex-column align-items-stretch">
            <div class="dashboard-chart-heading">
                <div class="card-title mb-0 d-flex align-items-center gap-2">
                    <span class="kpi-card-icon is-info dashboard-week-icon">
                        <i class="bx bxs-user-plus"></i>
                    </span>
                    {{ __('dashboard.lists.patients_title') }}
                </div>
                <a href="{{ route('patients.index') }}" class="dashboard-list-link">
                    {{ __('dashboard.lists.view_all') }}
                </a>
            </div>
            <span class="text-muted fs-12 mt-1">{{ __('dashboard.lists.patients_subtitle') }}</span>
        </div>
        <div class="card-body dashboard-list-body">
            @forelse($items as $item)
                <div class="dashboard-patient-row">
                    <span class="dashboard-today-avatar">{{ $item['initials'] }}</span>
                    <div class="dashboard-today-person">
                        <i class="bx bxs-user"></i>
                        <div class="dashboard-today-person-text">
                            <div class="dashboard-today-name">{{ $item['name'] }}</div>
                            <div class="dashboard-today-meta">{{ $item['document'] }}</div>
                        </div>
                    </div>
                    <div class="dashboard-patient-when">
                        <span>{{ $item['registered_date'] }}</span>
                        <span>{{ $item['registered_time'] }}</span>
                    </div>
                </div>
            @empty
                <div class="dashboard-chart-empty">
                    {{ __('dashboard.lists.patients_empty') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
