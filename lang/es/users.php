<?php

return [
    'title' => 'Usuarios',
    'table' => 'Tabla de usuarios',
    'resource' => 'Usuario',
    'add' => 'Agregar usuario',

    'modals' => [
        'create' => '¿Crear nuevo usuario?',
        'edit' => '¿Editar usuario?',
        'delete' => '¿Eliminar usuario?',
    ],

    'flash' => [
        'created_title' => 'Usuario creado',
        'created' => 'Usuario creado correctamente',
        'updated_title' => 'Usuario editado',
        'updated' => 'Usuario editado correctamente',
        'deleted_title' => 'Usuario eliminado',
        'deleted' => 'Usuario eliminado correctamente',
    ],

    'fields' => [
        'firstname' => 'Nombre',
        'lastname' => 'Apellido',
        'email' => 'Correo electrónico',
        'role' => 'Rol',
        'password' => 'Contraseña',
        'password_confirmation' => 'Confirmar contraseña',
    ],

    'placeholders' => [
        'select_role' => 'Seleccionar rol',
    ],

    'columns' => [
        'full_name' => 'Nombre',
        'email' => 'Correo electrónico',
        'role_name' => 'Rol',
    ],

    'cannot_assign_super_admin' => 'No tiene permiso para asignar el rol super-admin.',

    'errors' => [
        'cannot_delete_doctor' => 'No se puede eliminar el usuario porque tiene un perfil de médico asociado.',
        'cannot_delete_patient' => 'No se puede eliminar el usuario porque tiene un perfil de paciente asociado.',
        'cannot_edit_super_admin' => 'No se puede editar el super administrador.',
        'cannot_delete_self' => 'No puede eliminarse a sí mismo.',
        'cannot_delete_super_admin' => 'No se puede eliminar el super administrador.',
    ],
];
