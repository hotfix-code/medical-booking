<x-app-layout>

    @push('css')
        @include('components.includes.datatables.cdn-css')
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">{{ __('specialties.title') }}</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">{{ __('nav.categories.clinical') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('specialties.title') }}</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                {{ __('specialties.table') }}
                            </div>
                            @canOrRole('specialty.create', 'super-admin')
                                <div>
                                    <button class="btn btn-primary" id="add-new-row">{{ __('specialties.add') }}</button>
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

    <x-templates.specialty.form-tpl/>

    @push('scripts')
        @include('components.includes.datatables.cdn-js')
    @endpush

    @push('custom-scripts')
        {!! $dataTable->scripts() !!}
        @vite('resources/js/medical-booking/specialties.js')
    @endpush

</x-app-layout>
