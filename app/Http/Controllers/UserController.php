<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        $this->authorizeView('viewAny', User::class);
        return $dataTable->render('pages.users.index', [
            'roles' => Role::whereNotIn('name', ['super-admin', 'patient', 'doctor'])->get(),
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
    public function store(StoreUserRequest $request, UserService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', User::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UserService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $user);
        return $response ?? $service->update($user, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $user);
        return $response ?? $service->delete($user);
    }

    public function fetch(User $user, UserService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $user);
        return $response ?? $service->fetch($user);
    }
}
