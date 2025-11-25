<?php

namespace App\Http\Controllers;

use App\DataTables\PatientsDataTable;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\DocumentType;
use App\Services\PatientService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(PatientsDataTable $dataTable)
    {
        $this->authorizeView('viewAny', Patient::class);
        return $dataTable->render('pages.patients.index', [
            'documentTypes' => DocumentType::all(),
        ]);
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
    public function store(StorePatientRequest $request, PatientService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', Patient::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient, PatientService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $patient);
        return $response ?? $service->update($patient, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient, PatientService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $patient);
        return $response ?? $service->delete($patient);
    }

    public function fetch(Patient $patient, PatientService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $patient);
        return $response ?? $service->fetch($patient);
    }

    public function fetchAll(): JsonResponse
    {
        return DataTables::eloquent(Patient::query())->toJson();
    }
}
