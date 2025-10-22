<?php

namespace App\Http\Controllers;

use App\Models\BookingInfo;
use App\Models\Permission;
use App\Models\Pricing;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Tenant;
use App\Models\TenantInventory;
use App\Models\TenantUser;
use App\Models\UserReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Support\Facades\Mail;

class TenantController extends Controller
{
    public function tenantLogin()
    {
        return view('tenant.login');
    }

    public function tenantRegister()
    {
        return view('tenant.register');
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
        if (!empty($tenant)) {
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
            foreach ($permissions as $permission) {
                RolePermission::create([
                    'role_id' => 1,
                    'permission_id' => $permission->id,
                ]);
            }
        }

        if (!empty($tenant)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
            ], 500);
        }
    }

    public function tenantDashboard()
    {
        return view('tenant.dashboard');
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
        $tenantInventorySeats = TenantInventory::where('tenant_id', session('tenant')['current_user']['tenant_id'])->where('type', 'seat')->get();
        $tenantInventoryUmbrellas = TenantInventory::where('tenant_id', session('tenant')['current_user']['tenant_id'])->where('type', 'umbrella')->get();
        $pricing = Pricing::where('is_active',1)->first();
        // dd($pricing);
        return view('tenant.seats', compact('tenantInventorySeats', 'tenantInventoryUmbrellas','pricing'));
    }

    public function tenantSeatsStore(Request $request)
    {
        // dd($request->all());
        // dd(session('tenant'));
        // $validator = Validator::make($request->all(), [
        //     'seats'                  => 'required|array',
        //     'seats.*.code'           => 'required|string|max:255',
        //     'seats.*.row'            => 'required|string|max:255',
        //     // Validate category as a string
        //     'seats.*.category'       => 'required|string|max:255',
        //     // Validate price as a number (integer or decimal)
        //     'seats.*.price'          => 'required|numeric',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status'  => 'error',
        //         'message' => 'Validation failed',
        //         'errors'  => $validator->errors()
        //     ], 422);
        // }

        if ($request->has('seats')) {
            foreach ($request->seats as $seat) {
                // Ensure $seat is treated as an array to access its keys
                TenantInventory::create([
                    'tenant_id'      => session('tenant')['current_user']['tenant_id'],
                    'tenant_user_id' => session('tenant')['current_user']['id'],
                    'serial_no'           => $seat['code'],
                    'row'            => $seat['row'],
                    // 'category'       => $seat['category'],
                    'price'          => $seat['price'], // Store the price field
                    'status'         => 'available',
                    'type' => 'seat',
                ]);
            }
        }
       return redirect()->back()->with('success', 'Seats added successfully');
    }

    public function tenantumbrellasStore(Request $request)
    {
        // dd($request->all());
        // dd(session('tenant'));
        $validator = Validator::make($request->all(), [
            'umbrellas'                  => 'required|array',
            // Validate price as a number (integer or decimal)
            'umbrellas.*.price'          => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        if ($request->has('umbrellas')) {
            foreach ($request->umbrellas as $seat) {
                // Ensure $seat is treated as an array to access its keys
                TenantInventory::create([
                    'tenant_id'      => session('tenant')['current_user']['tenant_id'],
                    'tenant_user_id' => session('tenant')['current_user']['id'],
                    'serial_no'      => $seat['umbrella_number'],
                    'row'            => $seat['row'] ?? null,
                    'category'       => $seat['category'] ?? null,
                    'price'          => $seat['price'] ?? null, // Store the price field
                    'status'         => 'available',
                    'type' => 'umbrella',
                ]);
            }
        }
       return redirect()->back()->with('success', 'Seats added successfully');
    }

    public function tenantLogout()
    {
        auth('tenant')->logout();
        Session::flush('tenant');
        return redirect()->route('tenant.login');
    }

    public function tenantUsersList()
    {
        $tenantUsers = TenantUser::where('tenant_id', session('tenant')['current_user']['tenant_id'])->where('is_admin','!=',1)->get();
        return view('tenant.tenant_users.list', compact('tenantUsers'));
    }

    public function tenantUsersCreate()
    {
        $roles = Role::all();
        return view('tenant.tenant_users.create', compact('roles'));
    }

    public function tenantUsersCreateProcess(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenant_users,email',
            'phone' => 'required|digits_between:10,15|unique:tenant_users,phone',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenantUser = TenantUser::create([
            'tenant_id' => $request->tenant_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'is_admin' => 0,
            'role_id' => $request->role_id,
            'status' => 1,
        ]);

        if (!empty($tenantUser)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tenant user created successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant user creation failed',
            ], 500);
        }
    }

    public function tenantRoles()
    {
        $roles = Role::all();
        return view('tenant.roles.list', compact('roles'));
    }

    public function tenantRoleCreate(Request $request)
    {
        // dd($request->all());
        Role::create([
            'name' => $request->role_name,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
        ], 200);
    }

    public function tenantSetPermissions(Request $request, $roleId)
    {
        // Validate role ID
        $role = Role::find($roleId);
        $permissions = Permission::all();
        $rolePermissions = RolePermission::where('role_id', $roleId)->pluck('permission_id')->toArray();
        return view('tenant.roles.set_permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function tenantSetPermissionsProcess(Request $request)
    {
        // dd($request->all());
        $roleId = $request->role_id;
        $permissions = $request->permissions;
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid role ID',
            ], 400);
        }
        RolePermission::where('role_id', $roleId)->delete();
        if (!empty($permissions) && is_array($permissions)) {
            foreach ($permissions as $permissionId) {
                RolePermission::create([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully',
        ], 200);
    }

    public function tenantUserEdit($id)
    {
        $tenantUser = TenantUser::find($id);
        $roles = Role::all();
        if (!$tenantUser) {
            return redirect()->back()->with('error', 'Tenant user not found');
        }
        return view('tenant.tenant_users.update', compact('tenantUser', 'roles'));
    }

    public function tenantUserUpdate(Request $request, $id)
    {
        // dd($request->all());
        $tenantUser = TenantUser::find($id);
        if (!$tenantUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant user not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:10,15|unique:tenant_users,phone,' . $id,
            'password' => 'nullable|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenantUser->name = $request->name;
        $tenantUser->phone = $request->phone;
        if (!empty($request->password)) {
            $tenantUser->password = bcrypt($request->password);
        }
        $tenantUser->role_id = $request->role_id;
        $tenantUser->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant user updated successfully',
        ], 200);
    }

    public function tenantInventoryPricing()
    {
        $pricing = Pricing::all();
        return view ('tenant.pricing',compact('pricing'));
    }

    public function tenantPricingStore(Request $request)
    {
        $validated = $request->validate([
            'season_name' => 'required|string|max:255',
            'price_per_seat' => 'required|numeric',
            'price_per_umbrella' => 'required|numeric',
        ]);

        Pricing::create([
            'name' => $validated['season_name'],
            'price_per_seat' => $validated['price_per_seat'],
            'price_per_umbrella' => $validated['price_per_umbrella'],
        ]);

        return response()->json(['message' => 'Season added successfully']);
    }

    public function tenantPricingUpdate(Request $request,$id)
    {
        $pricing = Pricing::find($id);
        if (!$pricing) {
            return redirect()->back()->with('error', 'Pricing not found');
        }
        // 🔹 Set all pricings to inactive first
        Pricing::query()->update(['is_active' => 0]);

        // 🔹 Activate the selected pricing
        $pricing->update(['is_active' => 1]);

        $tenantInventorySeats = TenantInventory::where('type', 'seat')->update(['price' => $pricing->price_per_seat]);
        $tenantInventoryUmbrellas = TenantInventory::where('type', 'umbrella')->update(['price' => $pricing->price_per_umbrella]);
        return redirect()->back()->with('success', 'Pricing updated successfully');

    }

    public function tenantUserReservations()
    {
        $reservations = UserReservation::with('user','pricing')->where('status','requested')->get();
        return view ('tenant.reservations',compact('reservations'));
    }

    public function tenantUserReservationMarkComplete($id)
    {
        $inventoriesUsed = BookingInfo::where('user_reservation_id',$id)->pluck('inventory_id')->toArray();
        // dd($inventoriesUsed);
        TenantInventory::whereIn('id', $inventoriesUsed)
            ->update(['status' => 'available']);

        // Mark reservation as completed
        UserReservation::where('id', $id)
            ->update(['status' => 'completed']);
        return redirect()->back();
    }

    public function tenantCreateManualReservation()
    {
        $pricings = Pricing::where('is_active',1)->get();
        return view('tenant.create_manual_reservation', compact('pricings'));
    }

     public function tenantSaveManualReservation(Request $request)
    {
        // dd($request->all());
        // Step 1: Validate request manually so we can return JSON
        $validator = Validator::make($request->all(), [
            'no_of_sets' => 'required|integer|min:1',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'room_number' => 'required',
            'booking_date' => 'required',
            'end_booking_date' => 'required',
            // 'booking_time' => 'required',
            'total_price' => 'required',
            'pricing_id' => 'required',
            'tower' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Step 2: Extract validated data
        $data = $validator->validated();

        $numberOfSets = $data['no_of_sets'];
        $addonSeats = $data['addon_seats'] ?? 0;
        $addonUmbrellas = $data['addon_umbrellas'] ?? 0;

        $numberOfSets = $data['no_of_sets'];
        $addonSeats = $data['addon_seats'] ?? 0;
        $addonUmbrellas = $data['addon_umbrellas'] ?? 0;
        $totalPrice = $data['total_price'];

        // Calculate total required seats & umbrellas
        $totalSeatsNeeded = ($numberOfSets * 2) + $addonSeats;
        $totalUmbrellasNeeded = ($numberOfSets * 1) + $addonUmbrellas;
        // dd($totalSeatsNeeded , $totalUmbrellasNeeded);
        // Fetch available seat inventory for this category & row
        $availableSeats = TenantInventory::where('type', 'seat')
            ->where('status', 'available')
            ->count();

        // Fetch available umbrella inventory for this category & row
        $availableUmbrellas = TenantInventory::where('type', 'umbrella')
            ->where('status', 'available')
            ->count();

        // Debug result
        if ($availableSeats >= $totalSeatsNeeded && $availableUmbrellas >= $totalUmbrellasNeeded) {
            try {
                // $stripe = new StripeClient(env('STRIPE_SECRET'));
                // $token = $stripe->tokens->create([
                //     'card' => [
                //         'number' => $data['card_number'],
                //         'exp_month' => $data['expiry_month'],
                //         'exp_year' => $data['expiry_year'],
                //         'cvc' => $data['cvc'],
                //     ],
                // ]);
                // if (!isset($token['id'])) {
                //     return response()->json(['status' => 'error', 'errors' => 'Token not created'], 422);
                // }
                // $customer = $stripe->customers->create([
                //     'email' => $data['email'],
                //     'name' => $data['first_name'] . ' ' . $data['last_name'],
                //     'source' => $token['id']
                // ]);

                // $charge = $stripe->charges->create([
                //     'amount' => (int)$totalPrice * 100,
                //     'currency' => 'usd',
                //     'customer' => $customer->id,
                //     'description' => 'Seat Booking for ' . $data['first_name'],
                // ]);
                $password = Str::random(8);
                $uniqueCode = strtoupper(Str::random(6));
                $user = User::create([
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone_number'],
                    'password' => bcrypt($password),
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'address' => $data['address'],
                    'unique_code' => $uniqueCode,
                ]);

                $userReservation = UserReservation::create([
                    'user_id' => $user->id,
                    'reservations' => json_encode($data),
                    'booking_date' => date('Y-m-d', strtotime($request->booking_date)),
                    'end_booking_date' => date('Y-m-d', strtotime($request->end_booking_date)),
                    // 'booking_start_time' => date('H:i:s', strtotime($request->booking_time)),
                    // 'booking_end_time' => $request->end_time ?? null,
                    'total_price' => $totalPrice,
                    'no_of_sets' => $numberOfSets,
                    'addon_seats' => $addonSeats,
                    'addon_umbrellas' => $addonUmbrellas,
                    'status' => 'requested',
                    'pricing_id' => $data['pricing_id'],
                    'room_number' => $data['room_number'],
                    'tower_preference' => $data['tower'],
                    'notes' => $data['address'],
                    'booked_by' => 'beach-manager',
                ]);

                $bookingDone = UserPayment::create([
                    'user_id' => $user->id,
                    'card_number' => $data['card_number'] ?? 'beach-manager',
                    'name_on_card' => $data['name_on_card'] ?? 'beach-manager',
                    'user_reservation_id' => $userReservation->id,
                    'amount' => $totalPrice,
                ]);

                $fetchSeats = TenantInventory::where('type', 'seat')
                        ->where('status', 'available')
                        ->inRandomOrder()
                        ->take($totalSeatsNeeded)
                        ->get();

                    // ✅ Fetch random available umbrellas
                $fetchUmbrellas = TenantInventory::where('type', 'umbrella')
                        ->where('status', 'available')
                        ->inRandomOrder()
                        ->take($totalUmbrellasNeeded)
                        ->get();

                TenantInventory::whereIn('id', $fetchSeats->pluck('id'))
                    ->update(['status' => 'booked']);
                TenantInventory::whereIn('id', $fetchUmbrellas->pluck('id'))
                    ->update(['status' => 'booked']);
                foreach ($fetchSeats as $seat) {
                    BookingInfo::create([
                        'user_id' => $user->id,
                        'inventory_id' => $seat->id,
                        'user_reservation_id' => $userReservation->id,
                        'type' => 'seat',
                    ]);
                }

                foreach ($fetchUmbrellas as $umbrella) {
                    BookingInfo::create([
                        'user_id' => $user->id,
                        'inventory_id' => $umbrella->id,
                        'user_reservation_id' => $userReservation->id,
                        'type' => 'umbrella',
                    ]);
                }
                if (!empty($bookingDone)) {
                    Mail::send([], [], function ($message) use ($data, $uniqueCode, $password) {
                        $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
                        $message->to($data['email']);
                        $message->subject('Your Beach Reservation Login Details');
                        $message->setBody('
                            Dear ' . e($data['first_name']) . ' ' . e($data['last_name']) . ',<br><br>
                            Your booking has been successfully created.<br><br>
                            <strong>Login Details:</strong><br>
                            Unique Code: <b>' . e($uniqueCode) . '</b><br>
                            Password: <b>' . e($password) . '</b><br><br>
                            Please keep this information safe for future reference.<br><br>
                            Best regards,<br>
                            <b>Beach Reservation Team</b>
                        ', 'text/html');
                    });
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Booking created successfully!',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'errors' => 'Payment failed: ' . $e->getMessage(),
                ], 500);
            }

        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'We cannot offer you the required number of sets.',
                'message' => 'tenant-failed',
                'available_seats' => $availableSeats,
                'available_umbrellas' => $availableUmbrellas,
            ], 403);
        }
    }

}
