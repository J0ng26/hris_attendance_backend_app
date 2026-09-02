<?php

namespace App\Modules\Otp\Service;

use App\Mail\OtpSender;
use App\Modules\Otp\Repository\OtpRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    private $otpRepository;

    public function __construct(OtpRepositoryInterface $otpRepository)
    {
        // Get the Interface and let it decide what implementor to use via provider
        $this->otpRepository = $otpRepository;
    }

    public function userAccountOtp(string $email, string $ip_address, string $user_id, string $receiver, string $usage)
    {
        Log::info("OTP generation");
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $count = $this->otpRepository->deleteByCondition([
            (object)[
                'column' => 'email',
                'value' => $email
            ],
            (object)[
                'column' => 'usage',
                'value' => $usage
            ],
            (object)[
                'column' => 'created_at',
                'value' => now()->startOfDay(),
                'operator' => '>=',
            ],
        ]);

        if ($count >= 5) {
            return response()->json([
                'message' => 'You have reached the maximum number of forgot password attempts for today.'
            ], 429);
        }

        $this->otpRepository->create([
            'user_id' => $user_id,
            'code' => $otpCode,
            'email' => $email,
            'expires_at' => Carbon::now()->addMinutes(15),
            'status' => 0,
            'reset_token' => null,
            'token_status' => 0,
            'usage' => $usage,
            'ip_address' => $ip_address,
        ]);

        Log::info("Generated OTP for $email for $usage: $otpCode");


        Log::info("OTP sent to $email for $usage: $otpCode");
        Mail::to($email)->send(new OtpSender($receiver, $otpCode, $usage));
        return response()->json(['message' => 'OTP sent successfully!']);
    }

    public function otpUsed(string $id)
    {
        $this->otpRepository->update(
            $id,
            [
                'status' => 1,
                'reset_token' => null
            ]
        );
    }

    public function verifyOtp(string $email, string $otpCode, string $usage)
    {
        $otp = $this->findByOtpCode($email, $otpCode, $usage);
        Log::info("Verifying OTP for email: $email, code: $otpCode, usage: $usage");
        Log::info($otp);

        if (!$otp) {
            $this->failedAttempt($email, $otpCode, $usage);
            return response()->json([
                'message' => 'OTP not found'
            ], 404);
        }
        if ($otp->status == 1) {
            $this->failedAttempt($email, $otpCode, $usage);
            return response()->json([
                'message' => 'OTP already used'
            ], 400);
        }
        if (Carbon::now()->greaterThan($otp->expires_at)) {
            $this->failedAttempt($email, $otpCode, $usage);
            return response()->json([
                'message' => 'OTP expired'
            ], 400);
        }
        if ((string)$otp->code !== (string)$otpCode) {
            $this->failedAttempt($email, $otpCode, $usage);
            return response()->json([
                'message' => 'Invalid OTP'
            ], 400);
        }
        $resetToken = Str::random(64);

        $this->otpRepository->update(
            $otp->id,
            [
                'reset_token' => hash('sha256', $resetToken),
                'status' => 1
            ]
        );
        return response()->json([
            'message' => 'OTP verified',
            'reset_token' => $resetToken
        ], 200);
    }

    public function findByEmailAndUsage(string $email, string $usage)
    {
        return $this->otpRepository->findByEmailAndUsage($email, $usage);
    }

    public function findByOtpCode(string $email, string $otpCode, string $usage)
    {
        return $this->otpRepository->findByOtpCode($email, $otpCode, $usage);
    }

    private function failedAttempt(string $email, string $otpCode, string $usage)
    {
        $otp = $this->findByOtpCode($email, $otpCode, $usage);

        if ($otp) {
            $this->otpRepository->update($otp->id, ['attempts' => $otp->attempts + 1]);
        }
    }
}
