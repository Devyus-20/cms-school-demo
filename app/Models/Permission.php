<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $primaryKey = 'id_permission';

    protected $fillable = ['name'];

    public function getIdAttribute()
    {
        return $this->getAttribute($this->primaryKey);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id', 'id_permission', 'id_role');
    }
}
