<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPayment;
use App\Models\UserReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function userReserveBookingLogin(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'card_number' => 'required|string|max:20',
            'name_on_card' => 'required|string|max:255',
            'expire_month' => 'required|min:1|max:12',
            'expire_year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 20),
            'cvc' => 'required|string|max:4',
        ]);

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $customerId = null;
        // $price = $request['amount'];
        $price = 100;
        $token = $stripe->tokens->create([
            'card' => [
                'number' => $request['card_number'],
                'exp_month' => $request['expire_month'],
                'exp_year' => $request['expire_year'],
                'cvc' => $request['cvc'],
            ],
        ]);
        if (!isset($token['id'])) {
            return response()->json(['status' => 'error', 'errors' => 'Token not created'], 422);
        }
        $customer = $stripe->customers->create([
            'email' => $request['email'],
            'name' => $request['first_name'] . ' ' . $request['last_name'],
            'source' => $token['id']
        ]);
        $customerId = $customer->id;

        $plan = $stripe->charges->create([
            'amount' => (int)$price * 100,
            'currency' => 'usd',
            'customer' => $customerId,
            'description' => 'Payment Recieved For '.$request['first_name'],
        ]);
        if($plan)
        {
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt(12345678),
                'city' => $request->city,
                'state' => $request->state,
                'address' => $request->address,
                'unique_code' => strtoupper(Str::random(6)),
            ]);
            if(!empty($user))
            {
                $userReservation = UserReservation::create([
                    'user_id' => $user->id,
                    'category_booked' => $request->category_selected,
                    'reservations' => json_encode($request->all()),
                    'booking_date' => date('Y-m-d', strtotime($request->booking_date)),
                    'booking_start_time' => $request->start_time ?? null,
                    'booking_end_time' => $request->end_time ?? null,
                    'total_price' => $price,
                ]);
                if(!empty($userReservation))
                {
                    $userPayment = UserPayment::create([
                        'user_id' => $user->id,
                        'card_number' => $request->card_number,
                        'name_on_card' => $request->name_on_card,
                        'user_reservation_id' => $userReservation->id,
                        'amount' => $price,
                    ]);
                    if(!empty($userPayment))
                    {

                        return response()->json(['status' => 'success', 'message' => 'User Created Successfully'], 200);
                    }
                }
                else{
                    return response()->json(['status' => 'error', 'errors' => 'Reservation Not Created'], 422);
                }
            }
            else{
                return response()->json(['status' => 'error', 'errors' => 'User Not Created'], 422);
            }


        }






    }
}
