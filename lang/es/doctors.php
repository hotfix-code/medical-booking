<?php

return [
    'title' => 'Médicos',
    'table' => 'Tabla de médicos',
    'resource' => 'Médico',
    'add' => 'Agregar médico',

    'modals' => [
        'create' => '¿Crear nuevo médico?',
        'edit' => '¿Editar médico?',
        'delete' => '¿Eliminar médico?',
    ],

    'flash' => [
        'created_title' => 'Médico creado',
        'created' => 'Médico creado correctamente',
        'updated_title' => 'Médico editado',
        'updated' => 'Médico editado correctamente',
        'deleted_title' => 'Médico eliminado',
        'deleted' => 'Médico eliminado correctamente',
    ],

    'fields' => [
        'document_type' => 'Tipo doc.',
        'document_number' => 'N.º documento',
        'license_number' => 'Número de licencia',
        'phone' => 'Teléfono',
        'specialties' => 'Especialidades',
    ],

    'placeholders' => [
        'select_document_type' => 'Seleccionar tipo de documento',
        'document_number' => 'Número de documento',
        'license_number' => 'Número de licencia',
        'phone_optional' => 'Teléfono (opcional)',
    ],

    'columns' => [
        'full_name' => 'Nombre del médico',
        'document_type' => 'Tipo doc.',
        'document_number' => 'N.º documento',
        'license_number' => 'Licencia',
        'phone' => 'Teléfono',
    ],

    'errors' => [
        'cannot_delete_appointments' => 'No se puede eliminar el médico porque tiene citas asociadas.',
        'cannot_delete_schedules' => 'No se puede eliminar el médico porque tiene horarios asociados.',
        'unable_create' => 'No se puede crear el médico en este momento. Inténtelo de nuevo más tarde.',
        'unable_update' => 'No se puede actualizar el médico en este momento.',
        'unable_delete' => 'No se puede eliminar el médico en este momento.',
        'unexpected_create' => 'Ocurrió un error inesperado al crear el médico.',
        'unexpected_update' => 'Ocurrió un error inesperado al actualizar el médico.',
        'unexpected_delete' => 'Ocurrió un error inesperado al eliminar el médico.',
    ],
];
