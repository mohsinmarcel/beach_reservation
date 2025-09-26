<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tenant_users';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'phone',
        'status',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
