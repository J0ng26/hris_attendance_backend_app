<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Modules\User\Service\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function authenticate(AuthRequest $request)
    {
        $validated = $request->validated();

        $result = $this->userService->authenticate(
            $validated['username'],
            $validated['password'],
            $request
        );

        if ($result && is_object($result) && method_exists($result, 'toArray')) {
            return new UserResource($result);
        }

        return $result;
    }

    public function user()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->load([
            'user_type',
            'department',
            'permissions'
        ]);

        $user->user_type->load([
            'permissions' => function ($query) use ($user) {
                $query->wherePivot('department_id', $user->department_id);
            },
        ]);

        return new UserResource($user);
    }

    public function resetPassword(AuthRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        return $this->userService->resetPassword(
            $user->id,
            $validated['old_password'],
            $validated['new_password']
        );
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return [
            'message' => 'Logged out successfully!'
        ];
    }

    public function forgotPassword(AuthRequest $request)
    {
        $validated = $request->validated();

        return $this->userService->forgotPassword(
            $validated['email'],
            $validated['ip_address']
        );
    }

    public function checkOtp(AuthRequest $request)
    {
        $validated = $request->validated();

        return $this->userService->checkOtp(
            $validated['email'],
            $validated['otp']
        );
    }

    public function changePassword(AuthRequest $request)
    {
        $validated = $request->validated();

        return $this->userService->changePassword(
            $validated['email'],
            $validated['token'],
            $validated['new_password']
        );
    }
}
