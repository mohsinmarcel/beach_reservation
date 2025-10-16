<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingInfo extends Model
{
    use HasFactory;
    protected $table = 'booking_info';
    protected $fillable = ['user_id','inventory_id','start_date','end_date','type','user_reservation_id'];
}
