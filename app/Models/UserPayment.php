<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    use HasFactory;
    protected $table = 'user_payments';
    protected $fillable = [
        'user_id',
        'card_number',
        'name_on_card',
        'user_reservation_id',
        'amount',
    ];
}
