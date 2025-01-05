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
     * save
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'cname' => 'required|string|max:255',
    //         'cemail' => 'required|string|email|unique:company,cemail',
    //         'name' => 'required|string|max:255', 
    //         'phone' => 'required|string|unique:company_users,phone_number',
    //         'email' => 'required|string|email|unique:company_users,email',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     DB::transaction(function () use ($request) {
    //         $company = DB::table('company')->insertGetId([
    //             'cname' => $request->cname,
    //             'cphone_number' => $request->phone,
    //             'cemail' => $request->cemail,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);

    //         $company_users = DB::table('company_users')->insert([
    //             'cid' => $company,
    //             'is_manager' => true,
    //             'name' => $request->name,
    //             'phone_number' => $request->phone,
    //             'email' => $request->email,
    //             'password' => $request->password, // already hashed by JWT
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);
    //     });

    //     $token = JWTAuth::fromUser($company_users);

    //     return redirect()->route('company.login');
    // }
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
