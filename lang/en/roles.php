<?php

return [
    'title' => 'Roles',
    'resource' => 'Role',
    'add' => 'Add New Role',
    'search' => 'Search Role',

    'modals' => [
        'create' => 'Create a new role?',
        'edit' => 'Edit the role?',
        'edit_permissions' => 'Edit Role Permissions',
        'delete' => 'Delete role?',
        'delete_confirm' => 'Are you sure you want to delete the role?',
    ],

    'flash' => [
        'created_title' => 'Role Created',
        'created' => 'Role created successfully',
        'updated_title' => 'Role Edited',
        'updated' => 'Role edited successfully',
        'permissions_updated' => 'Role permissions edited successfully',
        'deleted_title' => 'Role Deleted',
        'deleted' => 'Role deleted successfully',
    ],

    'fields' => [
        'name' => 'Role name',
        'members' => 'Members',
        'created' => 'Created',
        'permissions' => 'Permissions',
    ],

    'placeholders' => [
        'name' => 'Role name',
    ],

    'loading' => 'Loading Role data...',

    'errors' => [
        'cannot_delete_users' => 'Cannot delete role because it has users associated with it.',
        'cannot_edit_locked' => 'This role cannot be renamed.',
        'cannot_delete_locked' => 'This role cannot be deleted.',
        'cannot_use_locked_name' => 'This role name is reserved.',
        'cannot_edit_super_admin_permissions' => 'You can not edit permissions of the super administrator.',
    ],
];
