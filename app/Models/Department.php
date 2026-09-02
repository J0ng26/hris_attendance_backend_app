<?php

namespace App\Models;

use App\Traits\LogActivities;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasUuids, LogActivities;

    protected $guarded = [];

    protected $fillable = [
        'name'
    ];
}
