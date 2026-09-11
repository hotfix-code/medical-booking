<?php

return [
    'title' => 'Doctor Schedules',
    'breadcrumb' => 'Schedules',
    'resource' => 'Schedule',
    'add' => 'Create New Schedule',
    'calendar' => 'Calendar',

    'modals' => [
        'create' => 'Create new schedule?',
        'create_confirm' => 'This action confirms the schedule creation.',
        'edit' => 'Edit schedule?',
        'delete' => 'Delete schedule?',
    ],

    'flash' => [
        'created_title' => 'Schedule Created',
        'created' => 'Schedule created successfully',
        'updated_title' => 'Schedule Updated',
        'updated' => 'Schedule updated successfully',
        'deleted_title' => 'Schedule Deleted',
        'deleted' => 'Schedule deleted successfully',
        'fetched' => 'Schedules fetched successfully.',
    ],

    'errors' => [
        'conflict' => 'The selected time slot conflicts with another schedule. Please select a different time slot.',
        'cannot_delete_appointments' => 'Cannot delete schedule because it has appointments associated with it.',
    ],

    'fields' => [
        'consulting_room' => 'Consulting Room',
        'specialty' => 'Specialty',
        'doctor' => 'Doctor',
        'weekday' => 'Weekday',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
    ],

    'placeholders' => [
        'select_consulting_room' => 'Select Consulting Room',
        'select_specialty' => 'Select Specialty',
        'select_doctor' => 'Select Doctor',
        'select_weekday' => 'Select Weekday',
        'choose_time' => 'Choose time',
    ],
];
