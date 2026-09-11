@use('App\Enums\Role')

@props([
    'role',
])

@php
    $roleLabel = Role::label($role->name);
    $isLocked = Role::isLocked($role->name);
    $permissionsLocked = $role->name === Role::SuperAdmin->value;
@endphp

<div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 role-card" data-role-id="{{ $role->uuid }}">
    <div class="card custom-card">
        <div class="card-body">
            <div class="text-center">
                <span class="avatar avatar-xxl rounded">
                    <img src="{{ asset('assets/images/faces/placeholder.jpg') }}" alt="" class="rounded-circle role-avatar">
                </span>
            </div>
            <div class="d-flex  text-center justify-content-between mt-1 mb-3">
                <div class="flex-fill">
                    <p class="mb-0 fw-semibold fs-16 text-truncate max-w-150 mx-auto">
                        <a href="javascript:void(0);" class="role-name">
                            {{ $roleLabel }}
                        </a>
                    </p>
                    <p class="mb-0 fs-12 text-muted text-truncate max-w-150 mx-auto">
                        {{ __('common.fields.role') }}: <span class="role-name-upper">{{ $roleLabel }}</span>
                    </p>
                </div>
            </div>
            <div class="btn-list text-center">
                <div class="btn-list d-flex justify-content-center">
                    @canOrRole('role.edit')
                    <a aria-label="{{ __('common.actions.edit') }}"
                       class="btn btn-sm btn-icon btn-secondary-light btn-wave waves-effect waves-light p-0 action-edit d-flex {{ $isLocked ? 'is-locked' : '' }}"
                       title="{{ $isLocked ? __('roles.errors.cannot_edit_locked') : __('common.actions.edit') }}"
                       @if($isLocked) aria-disabled="true" tabindex="-1" @endif
                    >
                        <x-ui.lucide-icon name="square-pen" size="1rem" class="text-info role-card-icon m-auto"/>
                    </a>
                    <a aria-label="{{ __('roles.modals.edit_permissions') }}"
                       class="btn btn-sm btn-icon btn-primary-light btn-wave waves-effect waves-light p-0 action-edit-permissions d-flex {{ $permissionsLocked ? 'is-locked' : '' }}"
                       title="{{ $permissionsLocked ? __('roles.errors.cannot_edit_super_admin_permissions') : __('roles.modals.edit_permissions') }}"
                       @if($permissionsLocked) aria-disabled="true" tabindex="-1" @endif
                    >
                        <x-ui.lucide-icon name="key-round" size="1rem" class="text-primary role-card-icon m-auto"/>
                    </a>
                    @endcanOrRole

                    @canOrRole('role.delete')
                    <a aria-label="{{ __('common.actions.delete') }}"
                       class="btn btn-sm btn-icon btn-danger-light btn-wave waves-effect waves-light p-0 action-delete d-flex {{ $isLocked ? 'is-locked' : '' }}"
                       title="{{ $isLocked ? __('roles.errors.cannot_delete_locked') : __('common.actions.delete') }}"
                       @if($isLocked) aria-disabled="true" tabindex="-1" @endif
                    >
                        <x-ui.lucide-icon name="trash-2" size="1rem" class="text-danger role-card-icon m-auto"/>
                    </a>
                    @endcanOrRole
                </div>
            </div>
        </div>
        <div class="card-footer border-block-start-dashed text-center p-0">
            <div class="d-flex align-items-center justify-content-center">
                <div class="d-flex p-3 w-100 justify-content-center border-end">
                    <div class="text-center ">
                        <p class="fw-semibold mb-0">{{ __('roles.fields.members') }}</p>
                        <span class="text-muted fs-12 role-members">{{ $role->users->count() }}</span>
                    </div>
                </div>
                <div class="d-flex p-3 w-100 justify-content-center">
                    <div class="text-center">
                        <p class="fw-semibold mb-0">{{ __('roles.fields.created') }}</p>
                        <span class="text-muted fs-12 role-created-at">{{ $role->createAtDateFormat }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
