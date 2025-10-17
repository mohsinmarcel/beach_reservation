<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReservation extends Model
{
    use HasFactory;
    protected $table = 'user_reservations';
    protected $fillable = [
        'user_id',
        'category_booked',
        'reservations',
        'booking_date',
        'booking_start_time',
        'booking_end_time',
        'total_price',
        'number_of_umbrellas',
        'number_of_seats',
        'provider_tenant_id',
        'no_of_sets',
        'addon_seats',
        'addon_umbrellas',
        'pricing_id',
        'status',
        'room_number',
        'tower_preference',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class, 'pricing_id');
    }
}
