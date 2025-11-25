<?php

namespace App\Traits;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait UpdatesOwnUserAndSelf
{
    /**
     * Creates a new model instance associated with a user, assigns a role to the user,
     * and optionally loads specified relations.
     *
     * @param array $data The data used to create the user and model.
     * @param string $role The role to assign to the user.
     * @param array $userFields Fields that belong to the related user of the model.
     * @param array $relations Optional array of relations to load after creating the model.
     *
     * @return Model The created model instance associated with the user.
     */
    public function createWithUser(array $data, string $role, array $userFields, array $relations = []): Model
    {
        return DB::transaction(function () use ($data, $role, $userFields, $relations)
        {
            $userData = Arr::only($data, $userFields);
            $selfData = match ($role)
            {
                'patient' => Arr::except($data, $userFields),
                'doctor' => Arr::except($data, [...$userFields, 'specialties']),
            };

            $user = User::create($userData);
            $user->assignRole($role);

            $model = match ($role)
            {
                'patient' => $user->patient()->create($selfData),
                'doctor' => $user->doctor()->create($selfData),
            };

            if ($model instanceof Doctor)
            {
                $model->specialties()->sync($data['specialties']);
            }

            return !empty($relations) ? $model->load($relations) : $model;
        });
    }

    /**
     * Updates the given model with the provided data, splitting it into user-specific fields
     * and other fields, then optionally loads specified relations.
     *
     * @param Model $model The model instance to update.
     * @param array $data The data to update the model with.
     * @param array $userFields Fields that belong to the related user of the model.
     * @param array $relations Optional array of relations to load after updating the model.
     *
     * @return Model The updated model instance.
     */
    public function updateWithUser(Model $model, array $data, array $userFields, array $relations = []): Model
    {
        return DB::transaction(function () use ($model, $data, $userFields, $relations)
        {
            $userData = Arr::only($data, $userFields);

            $selfData = match (true)
            {
                $model instanceof Patient => Arr::except($data, $userFields),
                $model instanceof Doctor => Arr::except($data, [...$userFields, 'specialties']),
            };

            $model->user()->update($userData);
            $model->update($selfData);

            if ($model instanceof Doctor && isset($data['specialties']))
            {
                $model->specialties()->sync($data['specialties']);
            }

            $model->refresh();

            return !empty($relations) ? $model->load($relations) : $model;
        });
    }

    /**
     * Deletes the given model along with its associated user and detaches any related roles.
     *
     * @param Model $model The model instance to delete along with its related user and roles.
     *
     * @return void
     */
    public function deleteWithUser(Model $model)
    {
        return DB::transaction(function () use ($model)
        {
            $model->user->roles()->detach();
            $model->user()->delete();
            $model->delete();
        });
    }
}
