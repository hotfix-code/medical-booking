@props(['consultingRooms', 'doctors', 'weekdays', 'specialties'])

<template id="schedule-modal-template">
    <div class="">
        <div class="card custom-card">
            <div class="card-body p-0">
                <div class="border-bottom p-3">
                    <div class="text-start mb-4">
                        <label for="schedule-consulting-room-input-edit" class="form-label">Consulting Room</label>
                        <select class="form-control"
                                id="schedule-consulting-room-input-edit"
                        >
                            <option value>Select Consulting Room</option>
                            @foreach($consultingRooms as $consultingRoom)
                                <option value="{{ $consultingRoom->id }}">
                                    {{ $consultingRoom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-start mb-4">
                        <label for="schedule-specialty-select-edit" class="form-label">Specialty</label>
                        <select class="form-control"
                                id="schedule-specialty-select-edit"
                        >
                            <option value>Select Specialty</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}">
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-start mb-4">
                        <label for="schedule-doctor-input-edit" class="form-label">Doctor</label>
                        <select class="form-control"
                                id="schedule-doctor-input-edit"
                        >
                            <option value>Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">
                                    {{ $doctor->user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-start mb-4">
                        <label for="schedule-weekday-input-edit" class="form-label">Weekday</label>
                        <select class="form-control"
                                id="schedule-weekday-input-edit">
                            <option value>Select Weekday</option>
                            @foreach($weekdays as $weekday)
                                <option value="{{ $weekday->value }}">
                                    {{ __($weekday->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-start mb-4">
                        <label class="form-label" for="schedule-start-time-input-edit">Start Time</label>
                        <div class="input-group">
                            <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                            <input type="text"
                                   class="form-control"
                                   id="schedule-start-time-input-edit"
                                   placeholder="Choose time"
                            >
                        </div>
                    </div>
                    <div class="text-start mb-4">
                        <label class="form-label" for="schedule-end-time-input-edit">End Time</label>
                        <div class="input-group">
                            <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                            <input type="text"
                                   class="form-control"
                                   id="schedule-end-time-input-edit"
                                   placeholder="Choose time"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
