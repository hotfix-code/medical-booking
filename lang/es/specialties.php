<?php

return [
    'title' => 'Especialidades',
    'table' => 'Tabla de especialidades',
    'resource' => 'Especialidad',
    'add' => 'Agregar especialidad',

    'modals' => [
        'create' => '¿Crear nueva especialidad?',
        'view' => 'Ver especialidad',
        'edit' => '¿Editar especialidad?',
        'delete' => '¿Eliminar especialidad?',
    ],

    'flash' => [
        'created_title' => 'Especialidad creada',
        'created' => 'Especialidad creada correctamente',
        'updated_title' => 'Especialidad editada',
        'updated' => 'Especialidad editada correctamente',
        'deleted_title' => 'Especialidad eliminada',
        'deleted' => 'Especialidad eliminada correctamente',
    ],

    'errors' => [
        'cannot_delete_doctors' => 'No se puede eliminar la especialidad porque tiene médicos asociados.',
    ],

    'fields' => [
        'name' => 'Nombre',
        'description' => 'Descripción',
    ],

    'placeholders' => [
        'name' => 'Nombre',
        'description_optional' => 'Descripción (opcional)',
    ],

    'columns' => [
        'name' => 'Nombre',
        'description' => 'Descripción',
    ],
];
