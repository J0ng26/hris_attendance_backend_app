<?php

namespace App\Modules\Otp\Repository;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OtpRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByEmailAndUsage(string $email, string $usage): ?Model;
    public function findByOtpCode(string $email, string $otpCode, string $usage): ?Model;
}
