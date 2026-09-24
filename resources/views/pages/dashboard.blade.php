<x-app-layout>
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header dashboard-page-header">
                <div>
                    <h1 class="page-title mb-1">{{ __('dashboard.title') }}</h1>
                    <p class="dashboard-welcome mb-0">{{ $welcome }}</p>
                </div>
                <div
                    class="dashboard-clock"
                    data-dashboard-clock
                    data-iso="{{ $now->toIso8601String() }}"
                    data-timezone="{{ $timezone }}"
                >
                    <div class="dashboard-clock-icon">
                        <i class="bx bxs-calendar"></i>
                    </div>
                    <div>
                        <div class="dashboard-clock-date" data-clock="date">{{ ucfirst($now->isoFormat(__('dashboard.clock.date'))) }}</div>
                        <div class="dashboard-clock-time" data-clock="time">{{ $now->isoFormat(__('dashboard.clock.time')) }} {{ __('dashboard.clock.hours') }}</div>
                    </div>
                </div>
            </div>
            @php($kpiColumnClass = 'col-lg-6 col-md-6 col-sm-12 col-xxl-' . (int) (12 / max(count($kpis), 1)))
            <div class="row">
                @foreach($kpis as $kpi)
                    <x-ui.kpi-card
                        :title="$kpi['title']"
                        :value="$kpi['value']"
                        :subtitle="$kpi['comparison']"
                        :change="$kpi['change']"
                        :trend="$kpi['trend']"
                        :icon="$kpi['icon']"
                        :tone="$kpi['tone']"
                        :color="$kpi['color']"
                        :series="$kpi['series']"
                        :chart-id="$kpi['id']"
                        :column-class="$kpiColumnClass"
                    />
                @endforeach
            </div>
            <div class="row dashboard-charts-row">
                <x-ui.week-chart :chart="$weekChart" />
                <div class="col-12 dashboard-charts-pair">
                    <div class="row{{ $showSpecialties ? '' : ' dashboard-charts-pair-single' }}">
                        <x-ui.status-chart :chart="$statusChart" />
                        @if($showSpecialties)
                            <x-ui.top-specialties :chart="$topSpecialties" />
                        @endif
                    </div>
                </div>
            </div>
            <div class="row dashboard-list-row{{ $showTodayAppointments ? '' : ' dashboard-list-row-single' }}">
                @if($showTodayAppointments)
                    <x-ui.today-appointments :items="$todayAppointments" />
                @endif
                <div class="col-12 dashboard-charts-pair">
                    <div class="row{{ $showLatestPatients ? '' : ' dashboard-charts-pair-single' }}">
                        @if($showLatestPatients)
                            <x-ui.latest-patients :items="$latestPatients" />
                        @endif
                        <div class="col-12 dashboard-chart-side">
                            <x-ui.illustration-card :body="$illustrationBody" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        @vite('resources/js/medical-booking/dashboard.ts')
    @endpush
</x-app-layout>
