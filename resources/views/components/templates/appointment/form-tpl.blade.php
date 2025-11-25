@props(['patients', 'specialties', 'appointmentStatus'])

<template id="appointment-modal-template">
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-patient-select" class="form-label">Patient</label>
                    <select class="form-select" id="appointment-patient-select">
                        <option value>Select Patient</option>
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
                    <label for="appointment-specialty-select" class="form-label">Specialty</label>
                    <select class="form-select" id="appointment-specialty-select">
                        <option value="">Select Specialty</option>
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
                    <label for="appointment-doctor-select" class="form-label">Doctor</label>
                    <select class="form-select" id="appointment-doctor-select">
                        <option value="">Select Doctor</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-schedule-input" class="form-label">Schedule</label>
                    <input type="text" class="form-control" id="appointment-schedule-input" placeholder="Choose date">
                    <input type="hidden" id="appointment-schedule-input-hidden">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label class="form-label" for="appointment-date-input">Appointment Date</label>
                    <div class="input-group">
                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                        <input type="text"
                               class="form-control"
                               id="appointment-date-input"
                               placeholder="Choose Date"
                               disabled
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-time-select" class="form-label">Appointment Time</label>
                    <select class="form-select" id="appointment-time-select" disabled>
                        <option value="">Select Time</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-start mb-4">
                    <label for="appointment-consulting-room-input" class="form-label">Consulting Room</label>
                    <input type="text" class="form-control" disabled id="appointment-consulting-room-input">
                    <input type="hidden" id="appointment-consulting-room-input-hidden">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-status-select" class="form-label">Status</label>
                    <select class="form-select" id="appointment-status-select">
                        @foreach($appointmentStatus as $status)
                            <option value="{{ $status->value }}">{{ ucwords($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-start mb-4">
                    <label for="appointment-is-active-input" class="form-label">Active</label>
                    <input type="text" class="form-control" id="appointment-is-active-input" disabled value="Yes">
                </div>
            </div>
        </div>
        <div class="text-start mb-4">
            <label for="appointment-notes-input" class="form-label">Notes</label>
            <textarea class="form-control"
                      id="appointment-notes-input"
                      rows="3"
                      placeholder="Additional notes...">
            </textarea>
        </div>
    </div>
</template>
