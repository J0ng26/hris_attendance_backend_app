<?php

namespace App\Http\Requests;

class AuthRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route()?->getActionMethod();

        return match ($action) {
            'authenticate' => $this->authenticationRules(),
            'resetPassword' => $this->resetPasswordRules(),
            'forgotPassword' => $this->forgotPasswordRules(),
            'checkOtp' => $this->checkOtpRules(),
            'changePassword' => $this->changePasswordRules(),
            default => [],
        };
    }

    private function authenticationRules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string'
        ];
    }

    private function resetPasswordRules(): array
    {
        return [
            'old_password' => 'required|string',
            'new_password' => 'required|confirmed|string'
        ];
    }

    private function forgotPasswordRules(): array
    {
        return [
            'email' => 'required|email',
            'ip_address' => 'required|string'
        ];
    }

    private function checkOtpRules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6'
        ];
    }

    private function changePasswordRules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'new_password' => 'required|string|confirmed|min:8'
        ];
    }

}
