@use('App\Enums\Role')

<x-app-layout>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        @role('patient')
            <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" >
        @endrole
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">{{ __('profile.title') }}</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('profile.title') }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-3">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-body border-bottom">
                            <div class="d-sm-flex  main-profile-cover">
                                <span class="avatar avatar-xxl online me-3">
                                    <img src="{{ asset('assets/images/faces/5.jpg') }}" alt="" class="avatar avatar-xxl">
                                </span>
                                <div class="flex-fill main-profile-info my-auto">
                                    <h5 class="fw-semibold mb-1 ">{{ $user->full_name }}</h5>
                                    <div>
                                        <p class="mb-1 text-muted">{{ Role::label($user->role) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="p-4  border-bottom border-block-end-dashed">
                            <p class="fs-15 mb-2 me-4 fw-semibold">{{ __('profile.personal_info_label') }}</p>
                            <ul class="list-group">
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            {{ __('common.fields.name') }} :
                                        </div>
                                        <span class="fs-12 text-muted">{{ $user->full_name }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            {{ __('common.fields.email') }} :
                                        </div>
                                        <span class="fs-12 text-muted">{{ $user->email }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            {{ __('common.fields.phone') }} :
                                        </div>
                                        <span class="fs-12 text-muted">{{ $user->patient->phone ?? __('common.states.na') }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            {{ __('common.fields.role') }} :
                                        </div>
                                        <span class="fs-12 text-muted">{{ Role::label($user->role) }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class=" custom-card">
                                <div class="card-body p-0">
                                    <div class="border-block-end-dashed  bg-white rounded-2 p-2">
                                        <div>
                                            <ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block " id="myTab" role="tablist">
                                                <li class="nav-item rounded" role="presentation">
                                                    <button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
                                                            data-bs-target="#activity-tab-pane" type="button" role="tab"
                                                            aria-controls="activity-tab-pane" aria-selected="true"><i
                                                            class="ri-book-2-fill me-1 align-middle d-inline-block fs-16"></i>{{ __('profile.information') }}</button>
                                                </li>
                                                <li class="nav-item rounded" role="presentation">
                                                    <button class="nav-link" id="posts-tab" data-bs-toggle="tab"
                                                            data-bs-target="#posts-tab-pane" type="button" role="tab"
                                                            aria-controls="posts-tab-pane" aria-selected="false"><i
                                                            class="ri-lock-2-fill me-1 align-middle d-inline-block fs-16"></i>{{ __('profile.security') }}</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="py-4">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane show active fade p-0 border-0 bg-white rounded-3" id="activity-tab-pane"
                                                 role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @role('doctor')
                                                            @include('profile.partials.forms.doctor')
                                                        @elserole('patient')
                                                            @include('profile.partials.forms.patient')
                                                        @else
                                                            @include('profile.partials.forms.admin')
                                                        @endrole
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade p-0 border-0" id="posts-tab-pane"
                                                 role="tabpanel" aria-labelledby="posts-tab" tabindex="0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card border-0">
                                                            <div class="alert alert-primary border border-primary mb-0 p-3">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="me-2">
                                                                        <svg class="flex-shrink-0 svg-primary" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path></svg>
                                                                    </div>
                                                                    <div class="text-primary w-100">
                                                                        <div class="fw-semibold d-flex justify-content-between">
                                                                            {{ __('profile.password_change') }}
                                                                        </div>
                                                                        <div class="fs-12 op-8 mb-1">
                                                                            {{ __('profile.password_change_hint') }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('profile.partials.forms.update-password-form')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    @push('custom-scripts')
        @vite('resources/js/medical-booking/profile.js')
    @endpush

</x-app-layout>
