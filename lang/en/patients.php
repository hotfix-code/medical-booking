<?php

return [
    'title' => 'Patients',
    'table' => 'Patients Table',
    'resource' => 'Patient',
    'add' => 'Add Patient',
    'doctor_scope_info' => 'Only patients with scheduled appointments or assigned to your care are displayed.',

    'modals' => [
        'create' => 'Create new patient?',
        'edit' => 'Edit patient?',
        'delete' => 'Delete patient?',
    ],

    'flash' => [
        'created_title' => 'Patient Created',
        'created' => 'Patient created successfully',
        'updated_title' => 'Patient Edited',
        'updated' => 'Patient edited successfully',
        'deleted_title' => 'Patient Deleted',
        'deleted' => 'Patient deleted successfully',
    ],

    'errors' => [
        'unable_create' => 'Unable to create patient at this time. Please try again later.',
        'unable_update' => 'Unable to update patient at this time.',
        'unable_delete' => 'Unable to delete patient at this time.',
        'unexpected_create' => 'An unexpected error occurred while creating the patient.',
        'unexpected_update' => 'An unexpected error occurred while updating the patient.',
        'unexpected_delete' => 'An unexpected error occurred while deleting the patient.',
    ],

    'fields' => [
        'gender' => 'Gender',
        'document_type' => 'Document Type',
        'document_number' => 'Document Number',
        'birthdate' => 'Birthdate',
        'phone' => 'Phone',
    ],

    'placeholders' => [
        'select_gender' => 'Select Gender (Optional)',
        'select_document_type' => 'Select Document Type',
        'phone_optional' => 'Phone (Optional)',
        'birthdate' => 'YYYY-MM-DD',
    ],

    'columns' => [
        'full_name' => 'Patient Name',
        'document_type' => 'Doc. Type',
        'document_number' => 'Doc. Number',
        'phone' => 'Phone',
    ],
];
