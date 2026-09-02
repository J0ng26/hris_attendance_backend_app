<?php

namespace App\Models;

use App\Traits\LogActivities;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasUuids, LogActivities;

    protected $guarded = [];

    protected $fillable = [
        'id',
        'name',
        'level',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_type_permissions', 'user_type_id', 'permission_id');
    }
}
