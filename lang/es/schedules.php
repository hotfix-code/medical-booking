<?php

return [
    'title' => 'Horarios médicos',
    'breadcrumb' => 'Horarios',
    'resource' => 'Horario',
    'add' => 'Crear nuevo horario',
    'calendar' => 'Calendario',

    'modals' => [
        'create' => '¿Crear nuevo horario?',
        'create_confirm' => 'Esta acción confirma la creación del horario.',
        'edit' => '¿Editar horario?',
        'delete' => '¿Eliminar horario?',
    ],

    'flash' => [
        'created_title' => 'Horario creado',
        'created' => 'Horario creado correctamente',
        'updated_title' => 'Horario actualizado',
        'updated' => 'Horario actualizado correctamente',
        'deleted_title' => 'Horario eliminado',
        'deleted' => 'Horario eliminado correctamente',
        'fetched' => 'Horarios obtenidos correctamente.',
    ],

    'errors' => [
        'conflict' => 'El horario seleccionado entra en conflicto con otro. Elija otro intervalo.',
        'cannot_delete_appointments' => 'No se puede eliminar el horario porque tiene citas asociadas.',
    ],

    'fields' => [
        'consulting_room' => 'Consultorio',
        'specialty' => 'Especialidad',
        'doctor' => 'Médico',
        'weekday' => 'Día de la semana',
        'start_time' => 'Hora de inicio',
        'end_time' => 'Hora de fin',
    ],

    'placeholders' => [
        'select_consulting_room' => 'Seleccionar consultorio',
        'select_specialty' => 'Seleccionar especialidad',
        'select_doctor' => 'Seleccionar médico',
        'select_weekday' => 'Seleccionar día',
        'choose_time' => 'Elegir hora',
    ],
];
