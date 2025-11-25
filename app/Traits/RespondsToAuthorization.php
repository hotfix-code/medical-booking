<?php

namespace App\Traits;

use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

trait RespondsToAuthorization
{
    protected function authorizeView(string $ability, mixed $arguments = []): void
    {
        Gate::authorize($ability, $arguments);
    }

    protected function authorizeJson(string $ability, mixed $arguments = []): ?JsonResponse
    {
        $gate = Gate::inspect($ability, $arguments);
        return (!$gate->allowed())
            ? AppResponse::validation(message: $gate->message())
            : null;
    }
}
