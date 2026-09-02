<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTypePermission extends Pivot
{
    protected $table = 'user_type_permissions';

    protected $guarded = [];

    public $incrementing = true;

    protected $casts = [
        'user_type_id' => 'string',
        'department_id' => 'string',
        'permission_id' => 'string',
    ];
}
