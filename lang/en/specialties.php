<?php

return [
    'title' => 'Specialties',
    'table' => 'Specialties Table',
    'resource' => 'Specialty',
    'add' => 'Add Specialty',

    'modals' => [
        'create' => 'Create new specialty?',
        'view' => 'View specialty',
        'edit' => 'Edit specialty?',
        'delete' => 'Delete specialty?',
    ],

    'flash' => [
        'created_title' => 'Specialty Created',
        'created' => 'Specialty created successfully',
        'updated_title' => 'Specialty Edited',
        'updated' => 'Specialty edited successfully',
        'deleted_title' => 'Specialty Deleted',
        'deleted' => 'Specialty deleted successfully',
    ],

    'errors' => [
        'cannot_delete_doctors' => 'Cannot delete specialty because it has doctors associated with it.',
    ],

    'fields' => [
        'name' => 'Name',
        'description' => 'Description',
    ],

    'placeholders' => [
        'name' => 'Name',
        'description_optional' => 'Description (Optional)',
    ],

    'columns' => [
        'name' => 'Name',
        'description' => 'Description',
    ],
];
