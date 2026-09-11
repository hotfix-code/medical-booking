<?php

return [
    'title' => 'Pacientes',
    'table' => 'Tabla de pacientes',
    'resource' => 'Paciente',
    'add' => 'Agregar paciente',
    'doctor_scope_info' => 'Solo se muestran pacientes con citas programadas o asignados a su atención.',

    'modals' => [
        'create' => '¿Crear nuevo paciente?',
        'edit' => '¿Editar paciente?',
        'delete' => '¿Eliminar paciente?',
    ],

    'flash' => [
        'created_title' => 'Paciente creado',
        'created' => 'Paciente creado correctamente',
        'updated_title' => 'Paciente editado',
        'updated' => 'Paciente editado correctamente',
        'deleted_title' => 'Paciente eliminado',
        'deleted' => 'Paciente eliminado correctamente',
    ],

    'errors' => [
        'unable_create' => 'No se puede crear el paciente en este momento. Inténtelo de nuevo más tarde.',
        'unable_update' => 'No se puede actualizar el paciente en este momento.',
        'unable_delete' => 'No se puede eliminar el paciente en este momento.',
        'unexpected_create' => 'Ocurrió un error inesperado al crear el paciente.',
        'unexpected_update' => 'Ocurrió un error inesperado al actualizar el paciente.',
        'unexpected_delete' => 'Ocurrió un error inesperado al eliminar el paciente.',
    ],

    'fields' => [
        'gender' => 'Género',
        'document_type' => 'Tipo de documento',
        'document_number' => 'Número de documento',
        'birthdate' => 'Fecha de nacimiento',
        'phone' => 'Teléfono',
    ],

    'placeholders' => [
        'select_gender' => 'Seleccionar género (opcional)',
        'select_document_type' => 'Seleccionar tipo de documento',
        'phone_optional' => 'Teléfono (opcional)',
        'birthdate' => 'AAAA-MM-DD',
    ],

    'columns' => [
        'full_name' => 'Nombre del paciente',
        'document_type' => 'Tipo doc.',
        'document_number' => 'N.º documento',
        'phone' => 'Teléfono',
    ],
];
