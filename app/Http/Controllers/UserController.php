<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Models\TenantInventory;
use App\Models\User;
use App\Models\UserPayment;
use App\Models\UserReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function userReserveBookingLogin(Request $request)
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
            'card_number' => 'required|string|max:20',
            'name_on_card' => 'required|string|max:255',
            'expiry_month' => 'required',
            'expiry_year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 20),
            'cvc' => 'required|string|max:4',
            'room_number' => 'required',
            'booking_date' => 'required',
            'booking_time' => 'required',
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
            dd(1); // ✅ enough inventory
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'We cannot offer you the required number of sets.',
                'message' => 'tenant-failed',
                'available_seats' => $availableSeats,
                'available_umbrellas' => $availableUmbrellas,
            ], 403);
        }


        // // Step 3: Find valid tenant with enough matching seats
        // $tenantInventories = TenantInventory::where('type', 'seat')
        //     ->where('category', $category)
        //     ->where('row', $row)
        //     ->get()
        //     ->groupBy('tenant_id');

        // $validTenant = null;
        // $totalPrice = 0;

        // foreach ($tenantInventories as $tenantId => $items) {
        //     if ($items->count() >= $numberOfSeats) {
        //         $selectedSeats = $items->take($numberOfSeats);
        //         $totalPrice = $selectedSeats->sum('price');
        //         $validTenant = $tenantId;
        //         break;
        //     }
        // }

        // if (!$validTenant) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => 'No tenant has sufficient inventory matching your selection.',
        //                     'message' => 'tenant-failed'

        //     ], 403);
        // }

        // Step 4: Stripe Payment
        // try {
        //     $stripe = new StripeClient(env('STRIPE_SECRET'));

        //     $token = $stripe->tokens->create([
        //         'card' => [
        //             'number' => $data['card_number'],
        //             'exp_month' => $data['expire_month'],
        //             'exp_year' => $data['expire_year'],
        //             'cvc' => $data['cvc'],
        //         ],
        //     ]);

        //     if (!isset($token['id'])) {
        //         return response()->json(['status' => 'error', 'errors' => 'Token not created'], 422);
        //     }

        //     $customer = $stripe->customers->create([
        //         'email' => $data['email'],
        //         'name' => $data['first_name'] . ' ' . $data['last_name'],
        //         'source' => $token['id']
        //     ]);

        //     $charge = $stripe->charges->create([
        //         'amount' => (int)$totalPrice * 100,
        //         'currency' => 'usd',
        //         'customer' => $customer->id,
        //         'description' => 'Seat Booking for ' . $data['first_name'],
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => 'Payment failed: ' . $e->getMessage(),
        //     ], 500);
        // }

        // Step 5: Create User and Booking
        try {
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => bcrypt(12345678),
                'city' => $data['city'],
                'state' => $data['state'],
                'address' => $data['address'],
                'unique_code' => strtoupper(Str::random(6)),
            ]);

            $userReservation = UserReservation::create([
                'user_id' => $user->id,
                'provider_tenant_id' => $validTenant,
                'category_booked' => $category,
                'reservations' => json_encode($data),
                'booking_date' => date('Y-m-d', strtotime($request->booking_date)),
                'booking_start_time' => $request->start_time ?? null,
                'booking_end_time' => $request->end_time ?? null,
                'total_price' => $totalPrice,
                'number_of_umbrellas' => $numberOfUmbrellas ?? 0,
                'number_of_seats' => $numberOfSeats,
            ]);

            UserPayment::create([
                'user_id' => $user->id,
                'card_number' => $data['card_number'],
                'name_on_card' => $data['name_on_card'],
                'user_reservation_id' => $userReservation->id,
                'amount' => $totalPrice,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully!',
                'tenant_id' => $validTenant,
                'total_price' => $totalPrice,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Database error: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function userLoginProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_code'    => 'required',
            'login_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $reservedUser = User::where('unique_code', $request->unique_code)->first();

        if (!$reservedUser) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found'
            ], 401);
        }

        if (!Hash::check($request->login_password, $reservedUser->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (auth('user')->attempt([
            'unique_code' => $request->unique_code,
            'password'    => $request->login_password
        ])) {
            $reservedUser = auth('user')->user();
            session(['user' => $reservedUser]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Login successful',
                'user'    => $reservedUser,
            ], 200);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Login failed'
        ], 401);
    }

    public function userLogout(Request $request)
    {
        auth('user')->logout();
        session()->forget('user');
        return redirect()->back();
    }

    public function userCancellations()
    {
        $user = session('user');
        $cancellations = null;
        return view('user.cancellations', compact('cancellations'));
    }
    public function userReminders()
    {
        $user = session('user');
        $reminders = null;
        return view('user.reminders', compact('reminders'));
    }
    public function userBookings()
    {
        $user = session('user');
        $bookings = null;
        return view('user.booking', compact('bookings'));
    }

    public function getActivePricing()
    {
        $pricing = Pricing::where('is_active', 1)->first();
        // Defensive defaults if no record found
        $seatPrice = $pricing->price_per_seat ?? 10;
        $umbrellaPrice = $pricing->price_per_umbrella ?? 5;
        // Each set = 2 seats + 1 umbrella
        $baseSetPrice = ($seatPrice * 2) + $umbrellaPrice;
        return response()->json([
            'base_set' => $baseSetPrice,
            'seat' => $seatPrice,
            'umbrella' => $umbrellaPrice,
            'priceId' => $pricing->id,
        ]);
    }
}
