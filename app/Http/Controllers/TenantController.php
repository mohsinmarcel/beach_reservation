<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Tenant;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
            $tenantUser = TenantUser::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'is_admin' => 1,
                'role_id' => 1,
            ]);
            $permissions = Permission::all();
            foreach($permissions as $permission)
            {
                RolePermission::create([
                    'role_id' => 1,
                    'permission_id' => $permission->id,
                ]);
            }
        }

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
        $validator = Validator::make($request->all(), [
            'login_email'    => 'required|email',
            'login_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Find TenantUser by email
        $tenantUser = TenantUser::where('email', $request->login_email)->first();

        if (!$tenantUser || !Hash::check($request->login_password, $tenantUser->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Authenticate TenantUser using guard
        if (auth('tenant')->attempt([
            'email'    => $request->login_email,
            'password' => $request->login_password
        ])) {
           $tenantUser = auth('tenant')->user(); // correct guard
            $tenant     = $tenantUser->tenant;

            // Fetch permissions for this user
            $rolePermissions = RolePermission::with('permissions')
                ->where('role_id', $tenantUser->role_id)
                ->get();

            $permissionsGiven = $rolePermissions->pluck('permissions.name')->toArray();

            // Build structured array
            $tenantData = [
                        'tenant'       => $tenant->toArray(),
                        'current_user' => $tenantUser->toArray(),
                        'permissions'  => $permissionsGiven,
            ];

            // Store only tenant in session
            session(['tenant' => $tenantData]);



            // Example: Payment check at tenant level
            // if ($tenant->is_register_payment_done == 0) {
            //     return response()->json([
            //         'status' => 'redirect',
            //         'url'    => $tenant->payment_invoice_url
            //     ]);
            // }

            return response()->json([
                'status'  => 'success',
                'message' => 'Login successful',
                'tenant'  => $tenant,
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Login failed'
        ], 401);
    }


    public function tenantSeats()
    {
        return view ('tenant.seats');
    }

    public function tenantSeatsStore(Request $request)
    {
        dd($request->all());
    }

    public function tenantLogout()
    {
        auth('tenant')->logout();
        Session::flush('tenant');
        return redirect()->route('tenant.login');
    }

    public function tenantUsersList()
    {
        return view ('tenant.tenant_users.list');
    }

}
