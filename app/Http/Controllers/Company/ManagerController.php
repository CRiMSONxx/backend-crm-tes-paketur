<?php

namespace App\Http\Controllers\Company;

use App\Http\Requests\ManagerUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class ManagerController extends \App\Http\Controllers\Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Company/Auth/Register');
    }
    /**
     * Display the login view.
     */
    public function login(): Response
    {
        return Inertia::render('Company/Auth/Login');
    }
    /**
     * Display the user's manager form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Manager/Edit', [
            'mustVerifyEmail' => $request->user(),
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's manager information.
     */
    public function update(ManagerUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('manager.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
