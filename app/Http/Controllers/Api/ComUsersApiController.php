<?php

namespace App\Http\Controllers\Api;

use App\Models\Company_Users;
use App\Models\Company;
use App\Http\Requests\CompanyUsersStoreRequest;
use App\Http\Requests\CompanyUsersUpdateRequest;
use App\Http\Resources\CompanyUsersCollection;
use App\Http\Resources\CompanyUsersResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ComUsersApiController extends \App\Http\Controllers\Controller
{
    /**
     * Get all.
     */
    public function index()
    {
        $employee = Company_Users::query()
            ->select('company_users.*', 'company.cname', 'company.cemail')
            ->join('company', 'company_users.cid', '=', 'company.id')
            ->orderBy('company_users.created_at', 'desc')
            // ->filter(Request::only('search', 'trashed'))
            ->paginate(Request::input('per_page', 50))
            ->withQueryString();

        return response()->json([
            'employee' => $employee
        ],200);
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
    
        if (! auth()->user()->is_manager) {
            return response()->json(['error' => 'Forbidden: Manager access required'], 403);
        }

        $validator = Validator::make($request->all(), [
            'cid' => 'required|exists:company,id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:company_users,email',
            'password' => 'required|min:8',
            'address' => 'required|string',
            'is_manager' => 'boolean'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $request['password'] = Hash::make($request->password);
        
        $user = Company_Users::create($request->all());

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 200);
    }

    public function update(int $id, Request $request)
    {
    
        if (! auth()->user()->is_manager) {
            return response()->json(['error' => 'Forbidden: Manager access required'], 403);
        }
        
        $user = Company_Users::whereNull('deleted_at')->find($request->id);

        if (!$user) {
            return response()->json([
                'message' => 'User status deleted or not exists',
                'data'      => $user,
                'success' => false
            ], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => ['string','max:25',Rule::unique(Company_Users::class)->ignore($request->id)],
            'email' => ['email',Rule::unique(Company_Users::class)->ignore($request->id)],
            'address' => 'string',
            'address' => 'string',
            'current_password' => Rule::when($request->filled('password'), 'required|current_password'),
            'password' => Rule::when($request->filled('password'), ['min:8', 'confirmed']),
            'password_confirmation' => Rule::when($request->filled('password'), 'required')
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($request['password']) {
            $request['password'] = Hash::make($request->password);
        }

        $user->update($request->all());
    
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }

    public function destroy(int $id)
    {
        if (! auth()->user()->is_manager) {
            return response()->json(['error' => 'Forbidden: Manager access required'], 403);
        }
        
        $user = Company_Users::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User status deleted or not exists',
            ],200);
        }


        $user->update(['deleted_at' => now()]);
        
        return response()->json([
            'message' => 'User soft deleted at ' . now(),
            'data' => $user
        ], 200);
    }

    public function reactivate(int $id)
    {
        if (! auth()->user()->is_manager) {
            return response()->json(['error' => 'Forbidden: Manager access required'], 403);
        }
        
        $user = Company_Users::withTrashed()->find($id);

        $user->restore();
        
        return response()->json([
            'message' => 'User is reactivated',
            'data' => $user
        ], 200);
    }
}