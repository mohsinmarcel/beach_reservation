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
    ];
}
