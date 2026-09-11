<?php

return [
    'title' => 'Doctors',
    'table' => 'Doctors Table',
    'resource' => 'Doctor',
    'add' => 'Add Doctor',

    'modals' => [
        'create' => 'Create new doctor?',
        'edit' => 'Edit doctor?',
        'delete' => 'Delete doctor?',
    ],

    'flash' => [
        'created_title' => 'Doctor Created',
        'created' => 'Doctor created successfully',
        'updated_title' => 'Doctor Edited',
        'updated' => 'Doctor edited successfully',
        'deleted_title' => 'Doctor Deleted',
        'deleted' => 'Doctor deleted successfully',
    ],

    'fields' => [
        'document_type' => 'Doc. Type',
        'document_number' => 'Doc. Number',
        'license_number' => 'License Number',
        'phone' => 'Phone',
        'specialties' => 'Specialties',
    ],

    'placeholders' => [
        'select_document_type' => 'Select Document Type',
        'document_number' => 'Document Number',
        'license_number' => 'License Number',
        'phone_optional' => 'Phone (Optional)',
    ],

    'columns' => [
        'full_name' => 'Doctor Name',
        'document_type' => 'Doc Type',
        'document_number' => 'Doc Number',
        'license_number' => 'License',
        'phone' => 'Phone',
    ],

    'errors' => [
        'cannot_delete_appointments' => 'Cannot delete doctor because it has appointments associated with it.',
        'cannot_delete_schedules' => 'Cannot delete doctor because it has schedules associated with it.',
        'unable_create' => 'Unable to create doctor at this time. Please try again later.',
        'unable_update' => 'Unable to update doctor at this time.',
        'unable_delete' => 'Unable to delete doctor at this time.',
        'unexpected_create' => 'An unexpected error occurred while creating the doctor.',
        'unexpected_update' => 'An unexpected error occurred while updating the doctor.',
        'unexpected_delete' => 'An unexpected error occurred while deleting the doctor.',
    ],
];
