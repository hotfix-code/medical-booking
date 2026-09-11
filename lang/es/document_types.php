<?php

return [
    'title' => 'Tipos de documento',
    'table' => 'Tabla de tipos de documento',
    'resource' => 'Tipo de documento',
    'add' => 'Agregar tipo de documento',

    'modals' => [
        'create' => '¿Crear nuevo tipo de documento?',
        'edit' => '¿Editar tipo de documento?',
        'delete' => '¿Eliminar tipo de documento?',
    ],

    'flash' => [
        'created_title' => 'Tipo de documento creado',
        'created' => 'Tipo de documento creado correctamente',
        'updated_title' => 'Tipo de documento editado',
        'updated' => 'Tipo de documento editado correctamente',
        'deleted_title' => 'Tipo de documento eliminado',
        'deleted' => 'Tipo de documento eliminado correctamente',
    ],

    'fields' => [
        'name' => 'Nombre',
        'code' => 'Código',
    ],

    'placeholders' => [
        'name' => 'Nombre',
        'code' => 'Código',
    ],

    'columns' => [
        'name' => 'Nombre',
        'code' => 'Código',
    ],

    'errors' => [
        'cannot_delete_doctors' => 'No se puede eliminar el tipo de documento porque tiene médicos asociados.',
        'cannot_delete_patients' => 'No se puede eliminar el tipo de documento porque tiene pacientes asociados.',
    ],
];
