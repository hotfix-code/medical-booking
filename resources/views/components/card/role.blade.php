@props([
    'role',
])

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
                            {{ strtoupper(__($role->name)) }}
                        </a>
                    </p>
                    <p class="mb-0 fs-12 text-muted text-truncate max-w-150 mx-auto">
                        {{ __('Role') }}: <span class="role-name-upper">{{ strtoupper(__($role->name)) }}</span>
                    </p>
                </div>
            </div>
            <div class="btn-list text-center">
                <div class="btn-list d-flex justify-content-center">
                    @canOrRole('role.edit')
                    <a aria-label="button" class="btn btn-sm btn-icon btn-secondary-light btn-wave waves-effect waves-light p-0 action-edit d-flex" title="edit">
                        <x-ui.lucide-icon name="square-pen" size="1rem" class="text-info role-card-icon m-auto"/>
                    </a>
                    <a aria-label="button" class="btn btn-sm btn-icon btn-primary-light btn-wave waves-effect waves-light p-0 action-edit-permissions d-flex" title="edit-permission">
                        <x-ui.lucide-icon name="key-round" size="1rem" class="text-primary role-card-icon m-auto"/>
                    </a>
                    @endcanOrRole

                    @canOrRole('role.delete')
                    <a aria-label="button" class="btn btn-sm btn-icon btn-danger-light btn-wave waves-effect waves-light p-0 action-delete d-flex" title="delete">
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
                        <p class="fw-semibold mb-0">{{ __('Members') }}</p>
                        <span class="text-muted fs-12 role-members">{{ $role->users->count() }}</span>
                    </div>
                </div>
                <div class="d-flex p-3 w-100 justify-content-center">
                    <div class="text-center">
                        <p class="fw-semibold mb-0">{{ __('Created') }}</p>
                        <span class="text-muted fs-12 role-created-at">{{ $role->createAtDateFormat }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
