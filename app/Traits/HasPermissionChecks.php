<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\Response;

trait HasPermissionChecks
{
    /**
     * Evaluate whether the given user has the required role or permission and grant or deny access accordingly.
     *
     * @param User $user The user instance being checked.
     * @param string|array $permissions The permissions required to be checked against the user.
     * @param string|null $denyMessage The optional custom message returned when access is denied.
     *
     * @return Response The access decision as an allow or deny response.
     */
    protected function allowIfHasRoleOrCan(User $user, string|array $permissions, ?string $denyMessage = null): Response
    {
        if ($user->hasRole('super-admin'))
        {
            return Response::allow();
        }

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        if (array_any($permissions, fn($permission) => $user->can($permission)))
        {
            return Response::allow();
        }

        $message = $denyMessage ?? 'You do not have permission to perform this action.';

        return (request()->expectsJson())
            ? Response::deny($message)
            : Response::denyAsNotFound($message);
    }
}
