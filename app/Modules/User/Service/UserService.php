<?php

namespace App\Modules\User\Service;

use App\Mail\OtpSender;
use App\Mail\TemporaryPasswordSend;
use App\Models\User;
use App\Modules\ActivityLog\Service\ActivityLogService;
use App\Modules\Otp\Service\OtpService;
use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function getActivityLogService()
    {
        return app(ActivityLogService::class);
    }

    protected function getOtpService()
    {
        return app(OtpService::class);
    }

    public function generate()
    {
        return $this->generateSecureOtp(6);
    }

    private function generateSecureOtp($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $otp = '';
        $maxIndex = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[random_int(0, $maxIndex)];
        }

        return $otp;
    }

    public function checkUserCredentials(User|null $user, string $password): JsonResponse|bool
    {
        if (!$user || !Hash::check($password, $user->password)) {
            return Response::json([
                'message' => 'Invalid credentials!'
            ], 401);
        }

        if ($user) {
            if (!$user->active) {
                return Response::json([
                    'message' => 'Inactive user!'
                ], 403);
            }
        }

        return true;
    }

    public function all(
        int $userLevel,
        ?string $department = null,
        ?string $username = null
    ): Collection {
        return $this->userRepository
            ->index(['user_type', 'department'])
            ->filter(function ($user) use ($userLevel, $department, $username) {
                $targetLevel = $user->user_type?->level ?? 0;

                if ($targetLevel > $userLevel) {
                    return false;
                }

                if ($userLevel <= 80 && $userLevel >= 70) {
                    return $user->department?->name === $department;
                }

                if ($userLevel <= 60) {
                    return $user->username === $username;
                }

                return true;
            })
            ->values();
    }

    public function getUserNonAdmin(): Collection
    {
        $users = $this->userRepository->index(['user_type', 'department']);

        return $users->filter(
            function ($user) {
                return $user->user_type?->level < 100;
            }
        )->values();
    }

    public function add(string $username, string $first_name, ?string $middle_name, string $last_name, string $email, bool $active, string $user_type_id, ?string $department_id)
    {
        $tempPassword = $this->generate();
        $activationToken = 'ACT-' . strtoupper(Str::random(6));
        $tokenExpiresAt = Carbon::now()->addDays(2);

        $userAdded = $this->userRepository->create([
            'username' => $username,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'password' => bcrypt($tempPassword),
            'email' => $email,
            'active' => $active,
            'user_type_id' => $user_type_id,
            'department_id' => $department_id,
            'activation_token' => $activationToken,
            'activation_token_expires_at' => $tokenExpiresAt,
        ], ['user_type', 'department']);

        // Write activation link & email prominently to Laravel log
        $activationLink = "http://127.0.0.1:8000/activate?token={$activationToken}";
        Log::info("=================================================");
        Log::info(" [ACTIVATION LINK GENERATED FOR USER]");
        Log::info(" Email:            {$email}");
        Log::info(" Username:         {$username}");
        Log::info(" Full Name:        {$first_name} {$last_name}");
        Log::info(" Activation Token: {$activationToken}");
        Log::info(" Activation Link:  {$activationLink}");
        Log::info(" Expires At:       {$tokenExpiresAt->toDateTimeString()}");
        Log::info(" Temp Password:    {$tempPassword}");
        Log::info("=================================================");

        try {
            Mail::to($email)->send(new TemporaryPasswordSend($first_name . ' ' . $middle_name . ' ' . $last_name, $tempPassword));
        } catch (\Throwable $e) {
            Log::warning("[MAIL NOTICE] Could not dispatch mail: " . $e->getMessage());
        }

        return Response::json([
            'message' => 'User ' . $userAdded->first_name . ' ' . $userAdded->last_name . ' added successfully',
            'user' => $userAdded,
            'activation_token' => $activationToken,
            'activation_link' => $activationLink,
        ], 201);
    }

    public function edit(string $id, string $username, string $first_name, ?string $middle_name, string $last_name, string $email, bool $active, string $user_type_id, ?string $department_id)
    {
        $user = $this->userRepository->update(
            $id,
            [
                'username' => $username,
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'email' => $email,
                'active' => $active,
                'user_type_id' => $user_type_id,
                'department_id' => $department_id
            ],
            ['user_type', 'department']
        );

        return Response::json([
            'message' => 'User ' . $user->first_name . ' ' . $user->last_name . ' updated successfully',
            'user' => $user
        ], 200);
    }

    public function delete(string $id)
    {
        $data = $this->userRepository->findByPrimaryId($id);

        if (!$data) {
            return Response::json([
                'message' => 'User not found'
            ], 404);
        }

        $this->userRepository->delete($id);

        return Response::json([
            'message' => 'User ' . $data->first_name . ' ' . $data->last_name . ' deleted successfully'
        ], 200);
    }

    public function authenticate(string $username, string $password, Request $request): User|JsonResponse|null
    {
        $user = $this->userRepository->findByUsername($username, ['user_type', 'department', 'user_type.permissions']);

        $validationCheck = $this->checkUserCredentials($user, $password);

        if (!is_bool($validationCheck)) {
            return $validationCheck;
        }

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $activityLogService = $this->getActivityLogService();

            $lastLoginDate = $activityLogService->getUserLastLoginDate($user->id);

            $activityLogService->add(
                User::class,
                'authenticated',
                $user->id,
                [
                    'last_login_at' => $lastLoginDate
                ],
                [
                    'last_login_at' => now()->toDateTimeString()
                ]
            );

            $request->session()->regenerate();

            return $user;
        }

        return null;
    }

    public function resetPassword(string $id, string $old_password, string $new_password)
    {
        $user = $this->userRepository->findByPrimaryId($id, ['user_type', 'department']);

        if (!$user || !Hash::check($old_password, $user->password)) {
            return Response::json([
                'message' => 'The password is incorrect!'
            ], 400);
        }

        if ($user && Hash::check($new_password, $user->password)) {
            return Response::json([
                'message' => 'New password cannot be the same as the old password!'
            ], 400);
        }

        $user->update([
            'password' => bcrypt($new_password),
            'first_use' => false
        ]);

        return $user;
    }

    public function forgotPassword(string $email, string $ip_address)
    {
        Log::info("Forgot password request received for email: $email from IP: $ip_address");
        $otpService = $this->getOtpService();

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            Log::info("User not found for email: $email");
            return;
        }

        $user_name = $user->first_name . ' ' . $user->last_name;
        $usage = "Change Password";


        return $otpService->userAccountOtp($email, $ip_address, $user->id, $user_name, $usage);
    }

    public function checkOtp(string $email, string $code)
    {
        $otpService = $this->getOtpService();

        return $otpService->verifyOtp($email, $code, 'Change Password');
    }

    public function changePassword(string $email, string $token, string $newPassword)
    {
        $otpService = $this->getOtpService();

        $otp = $otpService->findByEmailAndUsage($email, 'Change Password');

        Log::info("Change password request received for email: $email");
        Log::info("Reset token: $token");
        Log::info("OTP: $otp");

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        if (hash('sha256', $token) !== $otp->reset_token) {
            return response()->json(['message' => 'Invalid reset token'], 400);
        }

        if ($otp->token_status == 1) {
            return response()->json(['message' => 'Reset token already used'], 400);
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 404);
        }

        $user->update([
            'password' => bcrypt($newPassword),
        ]);

        $otpService->otpUsed($otp->id);

        return response()->json(['message' => 'Password changed successfully']);
    }
}
