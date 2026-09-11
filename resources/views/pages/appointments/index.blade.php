<x-app-layout>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" >
        @include('components.includes.datatables.cdn-css')
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">{{ __('appointments.title') }}</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">{{ __('nav.categories.appointment') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('appointments.title') }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="d-flex justify-content-between align-items-baseline w-100">
                                <div class="card-title">
                                    {{ __('appointments.table') }}
                                </div>
                                @canOrRole('appointment.create', 'super-admin')
                                <div>
                                    <button class="btn btn-primary" id="add-new-row">{{ __('appointments.add') }}</button>
                                </div>
                                @endcanOrRole
                            </div>
                            @role('doctor')
                                <div class="alert-container w-100 my-2">
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <strong>{{ __('common.information') }}:</strong> {{ __('appointments.doctor_scope_info') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('common.actions.close') }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            @endrole
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-templates.appointment.form-tpl
        :patients="$patients"
        :specialties="$specialties"
        :appointmentStatus="$status"
    />

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{ asset('assets/libs/moment/moment.min.js') }}"></script>
        @include('components.includes.datatables.cdn-js')
    @endpush

    @push('custom-scripts')
        {!! $dataTable->scripts() !!}
        @vite('resources/js/medical-booking/appointments.js')
    @endpush

</x-app-layout>
