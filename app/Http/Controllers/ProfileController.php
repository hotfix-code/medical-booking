<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\DocumentType;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'documentTypes' => DocumentType::all(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ProfileService $service): JsonResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email'))
        {
            $request->user()->email_verified_at = null;
        }

        return $service->update($request->validated());
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function changeLocale(Request $request)
    {
        $request->validate([
            'locale' => ['required', 'string', 'exists:locales,code'],
        ]);

        $user = $request->user();
        $user->locale = $request->input('locale');
        $user->save();

        return back();
    }
}
