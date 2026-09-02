<?php

namespace App\Modules\Otp\Repository\Eloquent;

use App\Models\Otp;
use App\Modules\BaseRepository;
use App\Modules\Otp\Repository\OtpRepositoryInterface;

class OtpRepository extends BaseRepository implements OtpRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param Otp $model
     */
    public function __construct(Otp $model)
    {
        // Pass that model
        parent::__construct($model);
    }

    public function findByEmailAndUsage(string $email, string $usage): ?Otp
    {
        return $this->model->where('email', $email)
            ->where('usage', $usage)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function findByOtpCode(string $email, string $otpCode, string $usage): ?Otp
    {
        return $this->model->where('email', $email)->where('code', $otpCode)->where('usage', $usage)->first();
    }
}
