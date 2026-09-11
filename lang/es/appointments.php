<?php

return [
    'title' => 'Citas',
    'table' => 'Tabla de citas',
    'resource' => 'Cita',
    'add' => 'Agregar cita',
    'doctor_scope_info' => 'Mostrando solo sus citas. Las citas de otros médicos no se muestran.',

    'modals' => [
        'create' => '¿Crear nueva cita?',
        'edit' => '¿Editar cita?',
        'delete' => '¿Eliminar cita?',
    ],

    'flash' => [
        'created_title' => 'Cita creada',
        'created' => 'Cita creada correctamente',
        'updated_title' => 'Cita editada',
        'updated' => 'Cita editada correctamente',
        'deleted_title' => 'Cita eliminada',
        'deleted' => 'Cita eliminada correctamente',
        'slots_retrieved' => 'Cupos disponibles obtenidos correctamente.',
        'slots_specialty_retrieved' => 'Cupos disponibles de la especialidad obtenidos correctamente.',
        'availability_retrieved' => 'Disponibilidad del médico obtenida correctamente.',
    ],

    'fields' => [
        'patient' => 'Paciente',
        'specialty' => 'Especialidad',
        'doctor' => 'Médico',
        'schedule' => 'Horario',
        'appointment_date' => 'Fecha de la cita',
        'appointment_time' => 'Hora de la cita',
        'consulting_room' => 'Consultorio',
        'status' => 'Estado',
        'active' => 'Activo',
        'notes' => 'Notas',
    ],

    'placeholders' => [
        'select_patient' => 'Seleccionar paciente',
        'select_specialty' => 'Seleccionar especialidad',
        'select_doctor' => 'Seleccionar médico',
        'select_time' => 'Seleccionar hora',
        'select_status' => 'Seleccionar estado',
        'choose_date' => 'Elegir fecha',
        'choose_date_short' => 'Elegir fecha',
        'notes' => 'Notas adicionales...',
    ],

    'columns' => [
        'patient' => 'Paciente',
        'doctor' => 'Médico',
        'consulting_room' => 'Consultorio',
        'appointment_date' => 'Fecha',
        'appointment_time' => 'Hora',
        'status' => 'Estado',
        'is_active' => 'Activo',
    ],

    'errors' => [
        'patient_conflict' => 'El paciente seleccionado ya tiene una cita en esta fecha.',
        'schedule_mismatch' => 'El horario seleccionado no pertenece al médico indicado.',
        'slot_unavailable' => 'El horario seleccionado no está disponible.',
        'no_schedules' => 'No hay horarios para este día.',
        'no_schedules_doctor' => 'No hay horarios para este médico, especialidad y día.',
        'unable_slots' => 'No se pueden obtener los cupos disponibles.',
        'slots_error' => 'Ocurrió un error al obtener los cupos disponibles.',
        'slots_specialty_error' => 'Ocurrió un error al obtener los cupos disponibles de la especialidad.',
        'date_future' => 'La fecha debe ser futura.',
        'unable_availability' => 'No se puede obtener la disponibilidad del médico.',
        'availability_error' => 'Ocurrió un error al obtener la disponibilidad del médico.',
    ],
];
