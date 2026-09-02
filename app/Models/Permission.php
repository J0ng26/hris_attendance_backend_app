<?php

namespace App\Models;

use App\Traits\LogActivities;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Permission extends Model
{
    use HasUuids, HasFactory, Notifiable, LogActivities;

    protected $fillable = [
        'name',
        'permission_key',
    ];

    public function userTypes()
    {
        return $this->belongsToMany(UserType::class, 'user_type_permissions', 'permission_id', 'user_type_id');
    }
}
