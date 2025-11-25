<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate(['password' => ['required', 'confirmed', 'min:6', 'max:255', 'string']]);
        $request->user()->update(['password' => Hash::make($validated['password']),]);
        return AppResponse::success();
    }
}
