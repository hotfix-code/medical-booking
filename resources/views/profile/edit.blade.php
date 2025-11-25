<x-app-layout>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        @role('patient')
            <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" >
        @endrole
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title my-auto">Profile</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Start::row-1 -->
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
                                        <p class="mb-1 text-muted">{{ ucwords($user->roles[0]->name, '- ') }}</p>
{{--                                        <p class="fs-12 op-7 mb-0">--}}
{{--                                            <span class="me-3 d-inline-flex align-items-center"><i class="ri-building-line me-1 align-middle"></i>Georgia</span>--}}
{{--                                            <span class="d-inline-flex align-items-center"><i class="ri-map-pin-line me-1 align-middle"></i>Washington D.C</span>--}}
{{--                                        </p>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                        <div class="card-body p-0 main-profile-info">--}}
{{--                            <div class="d-flex align-items-center justify-content-between w-100">--}}
{{--                                <div class="py-3 border-end w-100 text-center">--}}
{{--                                    <p class="fw-bold fs-20  text-shadow mb-0">113</p>--}}
{{--                                    <p class="mb-0 fs-12 text-muted ">Projects</p>--}}
{{--                                </div>--}}
{{--                                <div class="py-3 border-end w-100 text-center">--}}
{{--                                    <p class="fw-bold fs-20  text-shadow mb-0">12.2k</p>--}}
{{--                                    <p class="mb-0 fs-12 text-muted ">Followers</p>--}}
{{--                                </div>--}}
{{--                                <div class="py-3 w-100 text-center">--}}
{{--                                    <p class="fw-bold fs-20  text-shadow mb-0">128</p>--}}
{{--                                    <p class="mb-0 fs-12 text-muted ">Following</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <div class="card custom-card">
                        <div class="p-4  border-bottom border-block-end-dashed">
                            <p class="fs-15 mb-2 me-4 fw-semibold">Personal Info :</p>
                            <ul class="list-group">
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            Name :
                                        </div>
                                        <span class="fs-12 text-muted">{{ $user->full_name }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            Email :
                                        </div>
                                        <span class="fs-12 text-muted">{{ $user->email }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            Phone :
                                        </div>
                                        <span class="fs-12 text-muted">{{ $user->patient->phone ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item border-0">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="me-2 fw-semibold">
                                            Role :
                                        </div>
                                        <span class="fs-12 text-muted">{{ ucwords($user->role, '- ') }}</span>
                                    </div>
                                </li>
{{--                                <li class="list-group-item border-0">--}}
{{--                                    <div class="d-flex flex-wrap align-items-center">--}}
{{--                                        <div class="me-2 fw-semibold">--}}
{{--                                            Age :--}}
{{--                                        </div>--}}
{{--                                        <span class="fs-12 text-muted">28</span>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
                            </ul>
                        </div>
{{--                        <div class="p-4 border-bottom border-block-end-dashed">--}}
{{--                            <p class="fs-15 mb-2 me-4 fw-semibold">Contact Information :</p>--}}
{{--                            <div class="text-muted">--}}
{{--                                <p class="mb-3">--}}
{{--                                    <span class="avatar avatar-sm avatar-rounded me-2 bg-info-transparent">--}}
{{--                                                <i class="ri-mail-line align-middle fs-14"></i>--}}
{{--                                            </span>--}}
{{--                                    {{ Auth::user()->email }}--}}
{{--                                </p>--}}
{{--                                <p class="mb-3">--}}
{{--                                    <span class="avatar avatar-sm avatar-rounded me-2 bg-warning-transparent">--}}
{{--                                                <i class="ri-phone-line align-middle fs-14"></i>--}}
{{--                                            </span>--}}
{{--                                    {{ Auth::user()->full_name }}--}}
{{--                                </p>--}}
{{--                                <div class="d-flex">--}}
{{--                                    <p class="mb-0">--}}
{{--                                                <span class="avatar avatar-sm avatar-rounded me-2 bg-success-transparent">--}}
{{--                                                    <i class="ri-map-pin-line align-middle fs-14"></i>--}}
{{--                                                </span>--}}
{{--                                    </p>--}}
{{--                                    <p class="mb-0">--}}
{{--                                        MIG-1-11, Monroe Street, Georgetown, Washington D.C, USA,20071 </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="p-4 border-bottom border-block-end-dashed d-flex align-items-center">--}}
{{--                            <p class="fs-15 mb-2 me-4 fw-semibold">Social Networks :</p>--}}
{{--                            <div class="btn-list mb-0">--}}
{{--                                <button class="btn btn-sm btn-icon btn-info-light btn-wave waves-effect waves-light">--}}
{{--                                    <i class="ri-facebook-line"></i>--}}
{{--                                </button>--}}
{{--                                <button class="btn btn-sm btn-icon btn-secondary-light btn-wave waves-effect waves-light">--}}
{{--                                    <i class="ri-twitter-line"></i>--}}
{{--                                </button>--}}
{{--                                <button class="btn btn-sm btn-icon btn-warning-light btn-wave waves-effect waves-light">--}}
{{--                                    <i class="ri-instagram-line"></i>--}}
{{--                                </button>--}}
{{--                                <button class="btn btn-sm btn-icon btn-success-light btn-wave waves-effect waves-light">--}}
{{--                                    <i class="ri-github-line"></i>--}}
{{--                                </button>--}}
{{--                                <button class="btn btn-sm btn-icon btn-danger-light btn-wave waves-effect waves-light">--}}
{{--                                    <i class="ri-youtube-line"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
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
                                                            class="ri-book-2-fill me-1 align-middle d-inline-block fs-16"></i>Information</button>
                                                </li>
                                                <li class="nav-item rounded" role="presentation">
                                                    <button class="nav-link" id="posts-tab" data-bs-toggle="tab"
                                                            data-bs-target="#posts-tab-pane" type="button" role="tab"
                                                            aria-controls="posts-tab-pane" aria-selected="false"><i
                                                            class="ri-lock-2-fill me-1 align-middle d-inline-block fs-16"></i>Security</button>
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
                                                        @role('super-admin')
                                                            @include('profile.partials.forms.admin')
                                                        @elserole('doctor')
                                                            @include('profile.partials.forms.doctor')
                                                        @elserole('patient')
                                                            @include('profile.partials.forms.patient')
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
                                                                            Password Change
                                                                        </div>
                                                                        <div class="fs-12 op-8 mb-1">
                                                                            You are updating your password. Please make sure to choose a secure one before continuing.
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
            <!--End::row-1 -->

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    @push('custom-scripts')
        @vite('resources/js/medical-booking/profile.js')
    @endpush

</x-app-layout>
