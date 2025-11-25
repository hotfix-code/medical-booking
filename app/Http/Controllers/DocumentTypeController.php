<?php

namespace App\Http\Controllers;

use App\DataTables\DocumentTypesDataTable;
use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Models\DocumentType;
use App\Services\DocumentTypeService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;

class DocumentTypeController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(DocumentTypesDataTable $dataTable)
    {
        $this->authorizeView('viewAny', DocumentType::class);
        return $dataTable->render('pages.document-types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentTypeRequest $request, DocumentTypeService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', DocumentType::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType, DocumentTypeService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $documentType);
        return $response ?? $service->update($documentType, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType, DocumentTypeService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $documentType);
        return $response ?? $service->delete($documentType);
    }

    public function fetch(DocumentType $documentType, DocumentTypeService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $documentType);
        return $response ?? $service->fetch($documentType);
    }
}
