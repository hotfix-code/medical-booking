<?php

namespace App\Http\Controllers;

use App\DataTables\AppointmentsDataTable;
use App\Enums\AppointmentStatus;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Specialty;
use App\Services\AppointmentService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(AppointmentsDataTable $dataTable)
    {
        $this->authorizeView('viewAny', Appointment::class);
        $specialties = Specialty::whereHas('doctors')->get();
        return $dataTable->render('pages.appointments.index', [
            'patients' => Patient::all(),
            'specialties' => $specialties,
            'status' => AppointmentStatus::cases(),
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
    public function store(StoreAppointmentRequest $request, AppointmentService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', Appointment::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment, AppointmentService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $appointment);
        return $response ?? $service->update($appointment, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment, AppointmentService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $appointment);
        return $response ?? $service->delete($appointment);
    }

    public function fetch(Appointment $appointment, AppointmentService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $appointment);
        return $response ?? $service->fetch($appointment);
    }
}
