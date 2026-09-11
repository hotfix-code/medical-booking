<?php

return [
    'title' => 'Roles',
    'resource' => 'Rol',
    'add' => 'Agregar nuevo rol',
    'search' => 'Buscar rol',

    'modals' => [
        'create' => '¿Crear un nuevo rol?',
        'edit' => '¿Editar el rol?',
        'edit_permissions' => 'Editar permisos del rol',
        'delete' => '¿Eliminar rol?',
        'delete_confirm' => '¿Está seguro de que desea eliminar el rol?',
    ],

    'flash' => [
        'created_title' => 'Rol creado',
        'created' => 'Rol creado correctamente',
        'updated_title' => 'Rol editado',
        'updated' => 'Rol editado correctamente',
        'permissions_updated' => 'Permisos del rol editados correctamente',
        'deleted_title' => 'Rol eliminado',
        'deleted' => 'Rol eliminado correctamente',
    ],

    'fields' => [
        'name' => 'Nombre del rol',
        'members' => 'Miembros',
        'created' => 'Creado',
        'permissions' => 'Permisos',
    ],

    'placeholders' => [
        'name' => 'Nombre del rol',
    ],

    'loading' => 'Cargando datos del rol...',

    'errors' => [
        'cannot_delete_users' => 'No se puede eliminar el rol porque tiene usuarios asociados.',
        'cannot_edit_locked' => 'Este rol no se puede renombrar.',
        'cannot_delete_locked' => 'Este rol no se puede eliminar.',
        'cannot_use_locked_name' => 'Este nombre de rol está reservado.',
        'cannot_edit_super_admin_permissions' => 'No se pueden editar los permisos del super administrador.',
    ],
];
