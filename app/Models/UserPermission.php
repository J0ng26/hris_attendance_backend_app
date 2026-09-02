<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPermission extends Pivot
{
    protected $table = 'user_permission';

    protected $guarded = [];

    public $incrementing = true;

    protected $casts = [
        'user_id' => 'string',
        'permission_id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
