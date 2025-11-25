<x-app-layout>

    @push('css')
        @include('components.includes.datatables.cdn-css')
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">Consulting Rooms</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">Scheduling</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Consulting Rooms</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="d-flex justify-content-between align-items-baseline w-100">
                                <div class="card-title">
                                    Consulting Rooms Table
                                </div>
                                @canOrRole('consulting_room.create', 'super-admin')
                                    <div>
                                        <button class="btn btn-primary" id="add-new-row">Add Consulting Room</button>
                                    </div>
                                @endcanOrRole
                            </div>
                            @role('doctor')
                                <div class="alert-container w-100 my-2">
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <strong>Information:</strong> All consulting rooms are currently displayed without any doctor-specific filters applied.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
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

    <x-templates.consulting-room.form-tpl/>

    @push('scripts')
        @include('components.includes.datatables.cdn-js')
    @endpush

    @push('custom-scripts')
        {!! $dataTable->scripts() !!}
        @vite('resources/js/medical-booking/consulting-rooms.js')
    @endpush

</x-app-layout>
