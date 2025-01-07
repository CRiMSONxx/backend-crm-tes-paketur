<?php

namespace App\Http\Controllers\Company\Auth;

use App\Models\Company_Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class JWTAuthController extends \App\Http\Controllers\Controller
{
    // Company_Users registration
    // api usage
    // return json
    public function register(Request $request)
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
                'password' => $request->password, // already hashed by jwt
                'created_at' => now(),
                'updated_at' => now()
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->toJson(), 400);
        }

        // return response()->json($user, 201);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (! $token = auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // dd($token);
        return $this->respondWithToken($token);
        //needfix
        // return Inertia::render('Company/Employee/Index', [
        //     'access_token' => $token
        // ]);
    }

  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}