<x-app-layout>

    @push('css')
        @include('components.includes.datatables.cdn-css')
    @endpush

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title my-auto">Document Types</h1>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">Configuration</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Document Types</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Document Types Table
                            </div>
                            @canOrRole('document_type.create', 'super-admin')
                                <div>
                                    <button class="btn btn-primary" id="add-new-row">Add Document Type</button>
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

    <x-templates.document-type.form-tpl/>

    @push('scripts')
        @include('components.includes.datatables.cdn-js')
    @endpush

    @push('custom-scripts')
        {!! $dataTable->scripts() !!}
        @vite('resources/js/medical-booking/document-types.js')
    @endpush

</x-app-layout>
