<?php

return [
    'title' => 'Users',
    'table' => 'Users Table',
    'resource' => 'User',
    'add' => 'Add User',

    'modals' => [
        'create' => 'Create new user?',
        'edit' => 'Edit user?',
        'delete' => 'Delete user?',
    ],

    'flash' => [
        'created_title' => 'User Created',
        'created' => 'User created successfully',
        'updated_title' => 'User Edited',
        'updated' => 'User edited successfully',
        'deleted_title' => 'User Deleted',
        'deleted' => 'User deleted successfully',
    ],

    'fields' => [
        'firstname' => 'First Name',
        'lastname' => 'Last Name',
        'email' => 'Email',
        'role' => 'Role',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
    ],

    'placeholders' => [
        'select_role' => 'Select Role',
    ],

    'columns' => [
        'full_name' => 'Name',
        'email' => 'Email',
        'role_name' => 'Role',
    ],

    'cannot_assign_super_admin' => 'You do not have permission to assign the super-admin role.',

    'errors' => [
        'cannot_delete_doctor' => 'Cannot delete user because it has a doctor profile associated with it.',
        'cannot_delete_patient' => 'Cannot delete user because it has a patient profile associated with it.',
        'cannot_edit_super_admin' => 'You can not edit the super administrator.',
        'cannot_delete_self' => 'You can not delete yourself.',
        'cannot_delete_super_admin' => 'You can not delete the super administrator.',
    ],
];
