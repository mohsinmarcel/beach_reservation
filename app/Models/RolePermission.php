<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $fillable = ['role_id', 'permission_id'];
    protected $table = 'role_permissions';

    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
