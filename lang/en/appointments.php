<?php

return [
    'title' => 'Appointments',
    'table' => 'Appointments Table',
    'resource' => 'Appointment',
    'add' => 'Add Appointment',
    'doctor_scope_info' => 'Showing your appointments only. Other doctors\' appointments are not displayed.',

    'modals' => [
        'create' => 'Create new appointment?',
        'edit' => 'Edit appointment?',
        'delete' => 'Delete appointment?',
    ],

    'flash' => [
        'created_title' => 'Appointment Created',
        'created' => 'Appointment created successfully',
        'updated_title' => 'Appointment Edited',
        'updated' => 'Appointment edited successfully',
        'deleted_title' => 'Appointment Deleted',
        'deleted' => 'Appointment deleted successfully',
        'slots_retrieved' => 'Available slots retrieved successfully.',
        'slots_specialty_retrieved' => 'Available slots for specialty retrieved successfully.',
        'availability_retrieved' => 'Doctor complete availability retrieved successfully.',
    ],

    'fields' => [
        'patient' => 'Patient',
        'specialty' => 'Specialty',
        'doctor' => 'Doctor',
        'schedule' => 'Schedule',
        'appointment_date' => 'Appointment Date',
        'appointment_time' => 'Appointment Time',
        'consulting_room' => 'Consulting Room',
        'status' => 'Status',
        'active' => 'Active',
        'notes' => 'Notes',
    ],

    'placeholders' => [
        'select_patient' => 'Select Patient',
        'select_specialty' => 'Select Specialty',
        'select_doctor' => 'Select Doctor',
        'select_time' => 'Select Time',
        'select_status' => 'Select a status',
        'choose_date' => 'Choose Date',
        'choose_date_short' => 'Choose date',
        'notes' => 'Additional notes...',
    ],

    'columns' => [
        'patient' => 'Patient',
        'doctor' => 'Doctor',
        'consulting_room' => 'Room',
        'appointment_date' => 'Date',
        'appointment_time' => 'Time',
        'status' => 'Status',
        'is_active' => 'Active',
    ],

    'errors' => [
        'patient_conflict' => 'The selected patient already has an appointment on this date.',
        'schedule_mismatch' => 'The selected schedule does not belong to the specified doctor.',
        'slot_unavailable' => 'The selected time slot is not available.',
        'no_schedules' => 'No schedules found for this day.',
        'no_schedules_doctor' => 'No schedules found for this doctor, specialty and day.',
        'unable_slots' => 'Unable to fetch available slots.',
        'slots_error' => 'An error occurred while retrieving available slots.',
        'slots_specialty_error' => 'An error occurred while retrieving available slots for specialty.',
        'date_future' => 'The date must be in the future.',
        'unable_availability' => 'Unable to fetch doctor availability.',
        'availability_error' => 'An error occurred while retrieving doctor availability.',
    ],
];
