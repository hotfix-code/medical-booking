<x-app-layout>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <link href="{{ asset('assets/libs/fullcalendar/main.min.css') }}" rel="stylesheet" >
        <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" >
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">{{ __('schedules.title') }}</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">{{ __('schedules.breadcrumb') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('schedules.title') }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3">
                    <div class="card custom-card">
                        @canOrRole('schedule.create')
                            <div class="card-header d-grid">
                                <button class="btn btn-primary-light btn-wave" id="add-new-schedule">
                                    <i class="ri-add-line align-middle me-1 fw-semibold d-inline-block"></i>{{ __('schedules.add') }}
                                </button>
                            </div>
                        @endcanOrRole
                        <div class="card-body p-0">
                            <div class="border-bottom p-3">
                                <div class="text-start mb-4">
                                    <label for="schedule-consulting-room-input" class="form-label">{{ __('schedules.fields.consulting_room') }}</label>
                                    <select class="form-control"
                                            id="schedule-consulting-room-input"
                                    >
                                        <option value>{{ __('schedules.placeholders.select_consulting_room') }}</option>
                                            @foreach($consultingRooms as $consultingRoom)
                                                <option value="{{ $consultingRoom->id }}">
                                                    {{ $consultingRoom->name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                @canOrRole('schedule.create')
                                    <div class="text-start mb-4">
                                        <label for="schedule-specialty-select" class="form-label">{{ __('schedules.fields.specialty') }}</label>
                                        <select class="form-control"
                                                id="schedule-specialty-select"
                                        >
                                            <option value>{{ __('schedules.placeholders.select_specialty') }}</option>
                                            @foreach($specialties as $specialty)
                                                <option value="{{ $specialty->id }}">
                                                    {{ $specialty->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-start mb-4">
                                        <label for="schedule-doctor-select" class="form-label">{{ __('schedules.fields.doctor') }}</label>
                                        <select class="form-control"
                                                id="schedule-doctor-select"
                                        >
                                            <option value>{{ __('schedules.placeholders.select_doctor') }}</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">
                                                    {{ $doctor->user->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-start mb-4">
                                        <label for="schedule-weekday-select" class="form-label">{{ __('schedules.fields.weekday') }}</label>
                                        <select class="form-control"
                                                id="schedule-weekday-select">
                                            <option value>{{ __('schedules.placeholders.select_weekday') }}</option>
                                            @foreach($weekdays as $weekday)
                                                <option value="{{ $weekday->value }}">
                                                    {{ __('enums.weekday.'.strtolower($weekday->name)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-start mb-4">
                                        <label class="form-label" for="schedule-start-time-input">{{ __('schedules.fields.start_time') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                                            <input type="text"
                                                   class="form-control"
                                                   id="schedule-start-time-input"
                                                   placeholder="{{ __('schedules.placeholders.choose_time') }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="text-start mb-4">
                                        <label class="form-label" for="schedule-end-time-input">{{ __('schedules.fields.end_time') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                                            <input type="text"
                                                   class="form-control"
                                                   id="schedule-end-time-input"
                                                   placeholder="{{ __('schedules.placeholders.choose_time') }}"
                                            >
                                        </div>
                                    </div>
                                @endcanOrRole
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">{{ __('schedules.calendar') }}</div>
                        </div>
                        <div class="card-body">
                            <div id='calendar2'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-templates.schedule.form-tpl
        :consultingRooms="$consultingRooms"
        :specialties="$specialties"
        :doctors="$doctors"
        :weekdays="$weekdays"
    />

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{--        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>--}}
        <script src="{{ asset('assets/libs/moment/moment.min.js') }}"></script>
        <script src="{{ asset('assets/libs/fullcalendar/main.min.js') }}"></script>
    @endpush

    @push('custom-scripts')
        @vite('resources/js/medical-booking/schedules.js')
    @endpush

</x-app-layout>
