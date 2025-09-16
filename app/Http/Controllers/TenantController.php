<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function tenantDashboard()
    {
        return view ('tenant.dashboard');
    }

     public function tenantLoginProcess(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'login_email' => 'required',
            'login_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Try to find user by email OR username
        $tenant = Tenant::where('email', $request->login_email)
                    // ->orWhere('username', $request->login_email)
                    ->first();

        if (!$tenant || !Hash::check($request->login_password, $tenant->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Try login with email if exists, otherwise username
        if (auth('tenant')->attempt([
            filter_var($request->login_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $request->login_email,
            'password' => $request->login_password
        ])) {
            session(['tenant' => auth('tenant')->user()]);
            // $paymentCheck = auth('tenant')->user();

            // if ($paymentCheck->is_register_payment_done == 0) {
            //     return response()->json([
            //         'status' => 'redirect',
            //         'url'    => $paymentCheck->payment_invoice_url
            //     ]);
            // }

            return response()->json([
                'status'  => 'success',
                'message' => 'Login successful'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Login failed'
        ], 401);
    }


}
