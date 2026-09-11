<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

class AppResponse
{
    public static function success(
        mixed $data = [], string $message = 'OK', int $status = 200
    ): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $data,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'message' => $message,
                'code' => $status,
            ],
        ], $status);
    }

    public static function error(
        array|MessageBag $errors = [], ?string $message = null, int $status = 400
    ): JsonResponse
    {
        return response()->json([
            'status' => false,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'message' => $message ?? __('messages.oops'),
                'code' => $status,
            ],
        ], $status);
    }

    public static function validation(
        array|MessageBag $errors = [], ?string $message = null
    ): JsonResponse
    {
        return self::error($errors, $message ?? __('messages.validation_failed'), 422);
    }
}
