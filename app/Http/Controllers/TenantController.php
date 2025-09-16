<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function tenantLogin()
    {
        return view ('tenant.login');
    }

    public function tenantRegister()
    {
        return view ('tenant.register');
    }

    public function tenantRegisterProcess(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits_between:10,15|unique:tenants,phone',
            'password' => 'required|min:6',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'terms' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant = Tenant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        if(!empty($tenant))
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully',
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
            ], 500);
        }
    }

}
