<?php

namespace App\Http\Controllers\Api;

use App\Models\Company_Users;
use App\Models\Company;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ComApiController extends \App\Http\Controllers\Controller
{
    /**
     * Get all.
     */
    public function index(Request $request)
    {
        $company = Company::query()
            ->orderBy('company.created_at', 'desc')
            // ->filter($request->only('search', 'trashed'))
            ->paginate($request->input('per_page', 50))
            ->withQueryString();

        return response()->json([
            'company' => $company
        ],200);
    }
    /**
     * Get company.
     */
    public function show(int $id)
    {
        $company = Company::find($id);

        return response()->json([
            'company' => $company
        ],200);
    }
    /**
     * Get all employee within company. use company_users.cid 
     */
    public function show_employee(int $id, Request $request)
    {
        $employees = Company_Users::query()
            ->select('company_users.*', 'company.cname', 'company.cemail')
            ->join('company', 'company.id', '=', 'company_users.cid')
            ->where('company_users.cid', $id)
            ->orderBy('company_users.created_at', 'desc')
            ->paginate($request->input('per_page', 50))
            ->withQueryString();

        return response()->json([
            'employees' => $employees
        ], 200);
    }

    public function store(Request $request) 
    {
    
        if (! auth()->user()->is_super) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'cname' => 'required|string|max:255',
            'cemail' => 'required|string|email|unique:company,cemail',
            'name' => 'required|string|max:255', 
            'phone' => 'required|string|unique:company_users,phone_number',
            'email' => 'required|string|email|unique:company_users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        DB::beginTransaction();
        try {
            $company = DB::table('company')->insertGetId([
                'cname' => $request->cname,
                'cphone_number' => $request->phone,
                'cemail' => $request->cemail,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $company_users = Company_Users::create([
                'cid' => $company,
                'is_manager' => true,
                'name' => $request->name,
                'phone_number' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->toJson(), 400);
        }

        return response()->json([
            'message' => 'User and company created successfully',
            'data' => $company_users
        ], 201);
    }

    public function update(int $id, Request $request)
    {
    
        if (! auth()->user()->is_super) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        
        $user = Company::whereNull('deleted_at')->find($request->id);

        if (!$user) {
            return response()->json([
                'message' => 'User status deleted or not exists',
                'data'      => $user,
                'success' => false
            ], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => ['string','max:25',Rule::unique(Company::class)->ignore($request->id)],
            'email' => ['email',Rule::unique(Company::class)->ignore($request->id)],
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
        
        $user = Company::find($id);

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
        
        $user = Company::withTrashed()->find($id);

        $user->restore();
        
        return response()->json([
            'message' => 'User is reactivated',
            'data' => $user
        ], 200);
    }
}