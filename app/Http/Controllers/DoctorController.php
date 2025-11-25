<?php

namespace App\Http\Controllers;

use App\DataTables\DoctorsDataTable;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\DocumentType;
use App\Models\Specialty;
use App\Services\DoctorService;
use App\Services\AppointmentAvailabilityService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(DoctorsDataTable $dataTable)
    {
        $this->authorizeView('viewAny', Doctor::class);
        return $dataTable->render('pages.doctors.index', [
            'documentTypes' => DocumentType::all(),
            'specialties' => Specialty::all(),
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
    public function store(StoreDoctorRequest $request, DoctorService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', Doctor::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor, DoctorService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $doctor);
        return $response ?? $service->update($doctor, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor, DoctorService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $doctor);
        return $response ?? $service->delete($doctor);
    }

    public function fetch(Doctor $doctor, DoctorService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $doctor);
        return $response ?? $service->fetch($doctor);
    }


    public function fetchBySpecialty(Doctor $doctor, Specialty $specialty, DoctorService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $doctor);
        return $response ?? $service->fetchBySpecialty($doctor, $specialty);
    }

    public function fetchWithAvailability(Doctor $doctor, Request $request, AppointmentAvailabilityService $availabilityService): JsonResponse
    {
        $response = $this->authorizeJson('view', $doctor);
        if ($response) return $response;

        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        return $availabilityService->getDoctorCompleteAvailability($doctor->id, $request->date);
    }

    public function availableSlots(Doctor $doctor, Request $request, AppointmentAvailabilityService $service): JsonResponse
    {
        $response = $this->authorizeJson('view', $doctor);
        if ($response) return $response;

        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        return $service->getAvailableSlotsForDoctor($doctor->id, $request->date);
    }
}
