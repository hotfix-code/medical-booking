@props(['patients', 'specialties', 'appointmentStatus'])

<template id="appointment-modal-template">
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-patient-select" class="form-label">{{ __('appointments.fields.patient') }}</label>
                    <select class="form-select" id="appointment-patient-select">
                        <option value>{{ __('appointments.placeholders.select_patient') }}</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->user->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-specialty-select" class="form-label">{{ __('appointments.fields.specialty') }}</label>
                    <select class="form-select" id="appointment-specialty-select">
                        <option value="">{{ __('appointments.placeholders.select_specialty') }}</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-doctor-select" class="form-label">{{ __('appointments.fields.doctor') }}</label>
                    <select class="form-select" id="appointment-doctor-select">
                        <option value="">{{ __('appointments.placeholders.select_doctor') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-schedule-input" class="form-label">{{ __('appointments.fields.schedule') }}</label>
                    <input type="text" class="form-control" id="appointment-schedule-input" placeholder="{{ __('appointments.placeholders.choose_date_short') }}">
                    <input type="hidden" id="appointment-schedule-input-hidden">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label class="form-label" for="appointment-date-input">{{ __('appointments.fields.appointment_date') }}</label>
                    <div class="input-group">
                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                        <input type="text"
                               class="form-control"
                               id="appointment-date-input"
                               placeholder="{{ __('appointments.placeholders.choose_date') }}"
                               disabled
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-time-select" class="form-label">{{ __('appointments.fields.appointment_time') }}</label>
                    <select class="form-select" id="appointment-time-select" disabled>
                        <option value="">{{ __('appointments.placeholders.select_time') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-start mb-4">
                    <label for="appointment-consulting-room-input" class="form-label">{{ __('appointments.fields.consulting_room') }}</label>
                    <input type="text" class="form-control" disabled id="appointment-consulting-room-input">
                    <input type="hidden" id="appointment-consulting-room-input-hidden">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-status-select" class="form-label">{{ __('appointments.fields.status') }}</label>
                    <select class="form-select" id="appointment-status-select">
                        @foreach($appointmentStatus as $status)
                            <option value="{{ $status->value }}">{{ __('enums.appointment_status.'.$status->value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-is-active-input" class="form-label">{{ __('appointments.fields.active') }}</label>
                    <input type="text" class="form-control" id="appointment-is-active-input" disabled value="{{ __('common.states.yes') }}">
                </div>
            </div>
        </div>
        <div class="text-start mb-4">
            <label for="appointment-notes-input" class="form-label">{{ __('appointments.fields.notes') }}</label>
            <textarea class="form-control"
                      id="appointment-notes-input"
                      rows="3"
                      placeholder="{{ __('appointments.placeholders.notes') }}">
            </textarea>
        </div>
    </div>
</template>
