<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantInventory extends Model
{
    use HasFactory;
    protected $table = 'tenant_inventories';
    protected $fillable = [
        'tenant_id',
        'tenant_user_id',
        'row',
        'serial_no',
        'status',
        'category',
        'price',
        'type',
    ];
}
