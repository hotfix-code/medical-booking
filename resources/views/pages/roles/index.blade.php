<x-app-layout>

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">{{ __('roles.title') }}</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('roles.title') }}</li>
                    </ol>
                </div>
            </div>
            <div class="row" id="roles-grid">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="team-header">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">
                                    <div class="h5 fw-semibold mb-sm-0">{{ __('roles.title') }}</div>
                                    <div class="d-flex align-items-center">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="search-role-input" placeholder="{{ __('roles.search') }}" aria-describedby="search-team-member">
                                            <button aria-label="button" class="btn btn-light  btn-primary" type="button" id="search-team-member"><i class="ri-search-line"></i></button>
                                        </div>
                                        @canOrRole('role.create', 'super-admin')
                                            <div class="dropdown ms-2">
                                                <button aria-label="button" class="btn btn-light btn-wave" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <x-ui.lucide-icon name="ellipsis-vertical" size="1rem" class="m-0"/>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="javascript:void(0);"
                                                           id="add-new-role"
                                                        >
                                                            {{ __('roles.add') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endcanOrRole
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($roles as $role)
                    <x-card.role :role="$role"/>
                @endforeach

                <template id="role-card-template">
                    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 role-card" data-role-id="">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="text-center">
                                    <span class="avatar avatar-xxl rounded">
                                        <img src="" class="rounded-circle role-avatar">
                                    </span>
                                </div>
                                <div class="d-flex text-center justify-content-between mt-1 mb-3">
                                    <div class="flex-fill">
                                        <p class="mb-0 fw-semibold fs-16 text-truncate max-w-150 mx-auto role-name"></p>
                                        <p class="mb-0 fs-12 text-muted text-truncate max-w-150 mx-auto">
                                            {{ __('common.fields.role') }}: <span class="role-name-upper"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="btn-list d-flex justify-content-center">
                                    @canOrRole('role.edit')
                                        <a class="btn btn-sm btn-icon btn-secondary-light p-0 action-edit d-flex" title="{{ __('common.actions.edit') }}">
                                            <i data-lucide="square-pen" style="width: 1rem; height: 1rem;" class="role-card-icon m-auto text-info"></i>
                                        </a>
                                        <a class="btn btn-sm btn-icon btn-primary-light p-0 action-edit-permissions d-flex" title="{{ __('roles.modals.edit_permissions') }}">
                                            <i data-lucide="key-round" style="width: 1rem; height: 1rem;" class="role-card-icon m-auto"></i>
                                        </a>
                                    @endcanOrRole

                                    @canOrRole('role.delete')
                                        <a class="btn btn-sm btn-icon btn-danger-light p-0 action-delete d-flex" title="{{ __('common.actions.delete') }}">
                                            <i data-lucide="trash-2" style="width: 1rem; height: 1rem;" class="role-card-icon m-auto"></i>
                                        </a>
                                    @endcanOrRole
                                </div>
                            </div>
                            <div class="card-footer border-block-start-dashed text-center p-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="d-flex p-3 w-100 justify-content-center border-end">
                                        <div class="text-center">
                                            <p class="fw-semibold mb-0">{{ __('roles.fields.members') }}</p>
                                            <span class="text-muted fs-12 role-members">0</span>
                                        </div>
                                    </div>
                                    <div class="d-flex p-3 w-100 justify-content-center">
                                        <div class="text-center">
                                            <p class="fw-semibold mb-0">{{ __('roles.fields.created') }}</p>
                                            <span class="text-muted fs-12 role-created-at"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    @push('custom-scripts')
        @vite('resources/js/medical-booking/roles.js')
    @endpush

</x-app-layout>
