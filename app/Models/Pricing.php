<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;
    protected $table = 'pricings';
    protected $fillable = [
        'name',
        'price_per_seat',
        'price_per_umbrella',
        'is_active',
    ];
}
