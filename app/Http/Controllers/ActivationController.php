<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ActivationController extends Controller
{
    /**
     * Extracts token from either raw token string or full URL (e.g. http://.../activate?token=ACT-1234).
     */
    private function cleanToken(string $raw): string
    {
        $raw = trim($raw);
        if (str_contains($raw, '?')) {
            $parsed = parse_url($raw);
            if (!empty($parsed['query'])) {
                parse_str($parsed['query'], $queryParams);
                if (!empty($queryParams['token'])) {
                    return trim($queryParams['token']);
                }
            }
        }
        return $raw;
    }

    /**
     * Validates the activation token.
     * Implements flowchart node: "Activation link valid?"
     */
    public function validateToken(Request $request)
    {
        $rawToken = $request->input('token', '');
        $token = $this->cleanToken($rawToken);

        if (empty($token)) {
            return Response::json([
                'message' => 'Please enter your activation token.'
            ], 422);
        }

        /** @var User|null $user */
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return Response::json([
                'message' => "Show expired or invalid-link message\nContact main system support"
            ], 404);
        }

        // Check if token has expired
        if ($user->activation_token_expires_at && Carbon::now()->isAfter($user->activation_token_expires_at)) {
            return Response::json([
                'message' => "Show expired or invalid-link message\nContact main system support"
            ], 422);
        }

        $user->load(['department', 'user_type']);
        $fullName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        return Response::json([
            'valid' => true,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $fullName,
                'email' => $user->email,
                'department' => $user->department?->name ?? 'General',
                'position' => $user->user_type?->name ?? 'Employee',
            ]
        ], 200);
    }

    /**
     * Sets password and activates the user account.
     * Implements flowchart node: "Create password and activate account"
     */
    public function activateAccount(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $token = $this->cleanToken($validated['token']);

        /** @var User|null $user */
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return Response::json([
                'message' => "Show expired or invalid-link message\nContact main system support"
            ], 404);
        }

        if ($user->activation_token_expires_at && Carbon::now()->isAfter($user->activation_token_expires_at)) {
            return Response::json([
                'message' => "Show expired or invalid-link message\nContact main system support"
            ], 422);
        }

        $user->password = Hash::make($validated['password']);
        $user->active = true;
        $user->first_use = false;
        $user->activation_token = null;
        $user->activation_token_expires_at = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        Log::info("=================================================");
        Log::info(" [ACCOUNT ACTIVATED SUCCESSFULLY]");
        Log::info(" User ID:   {$user->id}");
        Log::info(" Username:  {$user->username}");
        Log::info(" Email:     {$user->email}");
        Log::info(" Activated: " . Carbon::now()->toDateTimeString());
        Log::info("=================================================");

        return Response::json([
            'message' => 'Account activated successfully! Please sign in with your password.'
        ], 200);
    }

    /**
     * Dynamically generates a pending user with random details and an activation token,
     * writes it to laravel.log, and returns the invitation for the mobile activation screen.
     */
    public function generateInvite(Request $request)
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $safeFirst = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $firstName));
        $safeLast = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $lastName));

        $username = $safeFirst . Str::lower(Str::random(3));
        $email = $safeFirst . '.' . $safeLast . rand(10, 99) . '@company.com';

        $token = 'ACT-' . strtoupper(Str::random(6));
        $expiresAt = Carbon::now()->addDays(2);

        $staffType = \App\Models\UserType::where('name', 'Staff')->first() 
            ?? \App\Models\UserType::first();

        $departments = [
            'Software Engineering',
            'Human Resources',
            'Finance & Accounting',
            'Operations',
            'Product & Design',
            'Sales & Marketing',
        ];
        $deptName = fake()->randomElement($departments);

        // Find or create department
        $dept = \App\Models\Department::firstOrCreate(['name' => $deptName]);

        $user = User::create([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make(Str::random(16)),
            'active' => false,
            'first_use' => true,
            'activation_token' => $token,
            'activation_token_expires_at' => $expiresAt,
            'user_type_id' => $staffType?->id,
            'department_id' => $dept->id,
        ]);

        $activationLink = "http://127.0.0.1:8000/activate?token={$token}";
        $fullName = "{$firstName} {$lastName}";

        Log::info("=================================================================");
        Log::info(" [AUTO-GENERATED ACTIVATION INVITE]");
        Log::info(" Email:            {$email}");
        Log::info(" Username:         {$username}");
        Log::info(" Full Name:        {$fullName}");
        Log::info(" Department:       {$deptName}");
        Log::info(" Position:         " . ($staffType?->name ?? 'Staff'));
        Log::info(" Activation Token: {$token}");
        Log::info(" Activation Link:  {$activationLink}");
        Log::info(" Status:           PENDING ACTIVATION (VALID)");
        Log::info(" Expires At:       {$expiresAt->toDateTimeString()}");
        Log::info(" Generated At:     " . Carbon::now()->toDateTimeString());
        Log::info("=================================================================");

        return Response::json([
            'token' => $token,
            'activation_link' => $activationLink,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $fullName,
                'email' => $user->email,
                'department' => $deptName,
                'position' => $staffType?->name ?? 'Staff',
            ]
        ], 201);
    }
}
