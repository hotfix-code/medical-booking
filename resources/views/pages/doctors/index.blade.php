<x-app-layout>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        @include('components.includes.datatables.cdn-css')
        <link href="{{ asset('assets/libs/choices/choices.min.css') }}" rel="stylesheet" >
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">Doctors</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">User Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Doctors</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Doctors Table
                            </div>
                            @canOrRole('doctor.create', 'super-admin')
                                <div>
                                    <button class="btn btn-primary" id="add-new-row">Add Doctor</button>
                                </div>
                            @endcanOrRole
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-templates.doctor.form-tpl :documentTypes="$documentTypes" :specialties="$specialties"/>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @include('components.includes.datatables.cdn-js')
        <script src="{{ asset('assets/libs/choices/choices.min.js') }}"></script>
    @endpush

    @push('custom-scripts')
        {!! $dataTable->scripts() !!}
        @vite('resources/js/medical-booking/doctor.js')
    @endpush

</x-app-layout>
