<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'status',
        'token_status',
        'usage',
        'ip_address',
        'email',
        'reset_token',
        'attempts',
    ];

    // Check if OTP is expired
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
