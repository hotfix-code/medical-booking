<?php

namespace App\Http\Controllers;

use App\DataTables\SpecialtiesDataTable;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Models\Specialty;
use App\Services\SpecialtyService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class SpecialtyController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(SpecialtiesDataTable $dataTable)
    {
        $this->authorizeView('viewAny', Specialty::class);
        return $dataTable->render('pages.specialties.index');
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
    public function store(StoreSpecialtyRequest $request, SpecialtyService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', Specialty::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialty $specialty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty, SpecialtyService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $specialty);
        return $response ?? $service->update($specialty, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty, SpecialtyService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $specialty);
        return $response ?? $service->delete($specialty);
    }

    public function fetch(Specialty $specialty, SpecialtyService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $specialty);
        return $response ?? $service->fetch($specialty);
    }

    public function fetchWithDoctors(Specialty $specialty, SpecialtyService $service)
    {
        $response = $this->authorizeJson('fetch', $specialty);
        return $response ?? $service->fetchWithDoctors($specialty);
    }
}
