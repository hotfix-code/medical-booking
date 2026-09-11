<?php

return [
    'title' => 'Document Types',
    'table' => 'Document Types Table',
    'resource' => 'Document Type',
    'add' => 'Add Document Type',

    'modals' => [
        'create' => 'Create new document type?',
        'edit' => 'Edit document type?',
        'delete' => 'Delete document type?',
    ],

    'flash' => [
        'created_title' => 'Document Type Created',
        'created' => 'Document type created successfully',
        'updated_title' => 'Document Type Edited',
        'updated' => 'Document type edited successfully',
        'deleted_title' => 'Document Type Deleted',
        'deleted' => 'Document type deleted successfully',
    ],

    'fields' => [
        'name' => 'Name',
        'code' => 'Code',
    ],

    'placeholders' => [
        'name' => 'Name',
        'code' => 'Code',
    ],

    'columns' => [
        'name' => 'Name',
        'code' => 'Code',
    ],

    'errors' => [
        'cannot_delete_doctors' => 'Cannot delete document type because it has doctors associated with it.',
        'cannot_delete_patients' => 'Cannot delete document type because it has patients associated with it.',
    ],
];
