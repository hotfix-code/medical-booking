@props(['consultingRooms', 'doctors', 'weekdays', 'specialties'])

<template id="schedule-modal-template">
    <div class="">
        <div class="card custom-card">
            <div class="card-body p-0">
                <div class="border-bottom p-3">
                    <div class="text-start mb-4">
                        <label for="schedule-consulting-room-input-edit" class="form-label">{{ __('schedules.fields.consulting_room') }}</label>
                        <select class="form-control"
                                id="schedule-consulting-room-input-edit"
                        >
                            <option value>{{ __('schedules.placeholders.select_consulting_room') }}</option>
                            @foreach($consultingRooms as $consultingRoom)
                                <option value="{{ $consultingRoom->id }}">
                                    {{ $consultingRoom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-start mb-4">
                        <label for="schedule-specialty-select-edit" class="form-label">{{ __('schedules.fields.specialty') }}</label>
                        <select class="form-control"
                                id="schedule-specialty-select-edit"
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
                        <label for="schedule-doctor-input-edit" class="form-label">{{ __('schedules.fields.doctor') }}</label>
                        <select class="form-control"
                                id="schedule-doctor-input-edit"
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
                        <label for="schedule-weekday-input-edit" class="form-label">{{ __('schedules.fields.weekday') }}</label>
                        <select class="form-control"
                                id="schedule-weekday-input-edit">
                            <option value>{{ __('schedules.placeholders.select_weekday') }}</option>
                            @foreach($weekdays as $weekday)
                                <option value="{{ $weekday->value }}">
                                    {{ __('enums.weekday.'.strtolower($weekday->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-start mb-4">
                        <label class="form-label" for="schedule-start-time-input-edit">{{ __('schedules.fields.start_time') }}</label>
                        <div class="input-group">
                            <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                            <input type="text"
                                   class="form-control"
                                   id="schedule-start-time-input-edit"
                                   placeholder="{{ __('schedules.placeholders.choose_time') }}"
                            >
                        </div>
                    </div>
                    <div class="text-start mb-4">
                        <label class="form-label" for="schedule-end-time-input-edit">{{ __('schedules.fields.end_time') }}</label>
                        <div class="input-group">
                            <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                            <input type="text"
                                   class="form-control"
                                   id="schedule-end-time-input-edit"
                                   placeholder="{{ __('schedules.placeholders.choose_time') }}"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
