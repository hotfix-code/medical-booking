@props([
    'items' => [],
])

<div class="col-12 dashboard-list-col">
    <div class="card custom-card dashboard-week-card">
        <div class="card-header flex-column align-items-stretch">
            <div class="dashboard-chart-heading">
                <div class="card-title mb-0 d-flex align-items-center gap-2">
                    <span class="kpi-card-icon is-warning dashboard-week-icon">
                        <i class="bx bxs-time-five"></i>
                    </span>
                    {{ __('dashboard.lists.today_title') }}
                </div>
                <a href="{{ route('appointments.index') }}" class="dashboard-list-link">
                    {{ __('dashboard.lists.view_all') }}
                </a>
            </div>
            <span class="text-muted fs-12 mt-1">{{ __('dashboard.lists.today_subtitle') }}</span>
        </div>
        <div class="card-body dashboard-list-body">
            @forelse($items as $item)
                <div class="dashboard-today-row">
                    <span class="dashboard-today-time">{{ $item['time'] }}</span>
                    <span class="dashboard-today-avatar">{{ $item['initials'] }}</span>
                    <div class="dashboard-today-person dashboard-today-copy">
                        <i class="bx bxs-user"></i>
                        <div class="dashboard-today-person-text">
                            <div class="dashboard-today-name">{{ $item['patient'] }}</div>
                            <div class="dashboard-today-meta">{{ $item['specialty'] }}</div>
                        </div>
                    </div>
                    <div class="dashboard-today-person dashboard-today-doctor">
                        <i class="bx bxs-first-aid"></i>
                        <div class="dashboard-today-person-text">
                            <div class="dashboard-today-name">{{ $item['doctor'] }}</div>
                            <div class="dashboard-today-meta">{{ __('dashboard.lists.doctor_label') }}</div>
                        </div>
                    </div>
                    <span class="dashboard-today-status is-{{ $item['status'] }}">
                        {{ __('enums.appointment_status.'.$item['status']) }}
                    </span>
                </div>
            @empty
                <div class="dashboard-chart-empty">
                    {{ __('dashboard.lists.today_empty') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
