<?php

namespace App\Http\Controllers\Api;

use App\Models\Company_Users;
use App\Models\Company;
use App\Http\Requests\CompanyUsersStoreRequest;
use App\Http\Requests\CompanyUsersUpdateRequest;
use App\Http\Resources\CompanyUsersCollection;
use App\Http\Resources\CompanyUsersResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ComUsersApiController extends \App\Http\Controllers\Controller
{
    public function index(): Response
    {
        $employees = Company_Users::query()
        ->select('company_users.*', 'company.cname', 'company.cemail')
        ->join('company', 'company_users.cid', '=', 'company.id')
        ->orderBy('company_users.created_at', 'desc')
        // ->filter(Request::only('search', 'trashed'))
        ->paginate(Request::input('per_page', 5))
        ->withQueryString();

        return Inertia::render('Company/Employee/Dashboard', [
            'filters' => Request::all('search', 'trashed'),
            'employees' => new CompanyUsersCollection($employees),
            'totalEmployees' => $employees->total(),
        ]);
    }
    /**
     * Get company_users.
     */
    public function show(int $id)
    {
        $employee = Company_Users::query()
            ->select('company_users.*', 'company.cname', 'company.cemail')
            ->join('company', 'company_users.cid', '=', 'company.id')
            ->where('company_users.id', $id)
            ->first();

        return response()->json([
            'employee' => $employee
        ],200);
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'cid' => 'required|exists:company,id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:company_users,email',
            'password' => 'required|min:6',
            'address' => 'required|string',
            'is_manager' => 'boolean'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        Company_Users::create($validated);

        return redirect()->route('company-users.index');
    }

    public function update(Request $request, Company_Users $user)
    {
        $validated = $request->validate([
            'cid' => 'required|exists:company,id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:company_users,email,' . $user->id,
            'address' => 'required|string',
            'is_manager' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('company-users.index');
    }

    public function destroy(Company_Users $user)
    {
        $user->update(['deleted_at' => now()]);
        
        return redirect()->route('company-users.index');
    }
}