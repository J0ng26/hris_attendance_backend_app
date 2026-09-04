<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateActivationLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:generate-activation 
                            {identifier? : Username, email, or user ID to generate activation link for}
                            {--expire-now : Mark the activation token as expired (for testing invalid-link branch)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and log an activation link for an account so you can set a password in the mobile app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $identifier = $this->argument('identifier');

        $user = null;
        if ($identifier) {
            $user = User::where('username', $identifier)
                ->orWhere('email', $identifier)
                ->orWhere('id', $identifier)
                ->first();

            if (!$user) {
                // Auto-create a pending employee account
                $isEmail = str_contains($identifier, '@');
                $email = $isEmail ? $identifier : strtolower($identifier) . '@company.com';
                $username = $isEmail ? explode('@', $identifier)[0] : $identifier;

                $user = User::create([
                    'username' => $username,
                    'first_name' => ucfirst($username),
                    'last_name' => 'Staff',
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)),
                    'active' => false,
                    'first_use' => true,
                    'user_type_id' => config('app.user_type_id') ?? \App\Models\UserType::first()?->id,
                ]);

                $this->info("Created new pending employee account: {$username} ({$email})");
            }
        } else {
            $user = User::first();
            if (!$user) {
                $this->error("No users found in database. Please run seeders first.");
                return 1;
            }
        }

        $token = 'ACT-' . strtoupper(Str::random(6));
        $isExpired = $this->option('expire-now');
        $expiresAt = $isExpired ? Carbon::now()->subHour() : Carbon::now()->addDays(2);

        $user->activation_token = $token;
        $user->activation_token_expires_at = $expiresAt;
        $user->save();

        $activationLink = "http://127.0.0.1:8000/activate?token={$token}";
        $fullName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        // 1. Write prominently to laravel.log
        Log::info("=================================================================");
        Log::info(" [MANUAL ACTIVATION LINK GENERATED]");
        Log::info(" Email:            {$user->email}");
        Log::info(" Username:         {$user->username}");
        Log::info(" Full Name:        {$fullName}");
        Log::info(" Activation Token: {$token}");
        Log::info(" Activation Link:  {$activationLink}");
        Log::info(" Status:           " . ($isExpired ? 'EXPIRED (FOR TESTING)' : 'VALID'));
        Log::info(" Expires At:       {$expiresAt->toDateTimeString()}");
        Log::info(" Generated At:     " . Carbon::now()->toDateTimeString());
        Log::info("=================================================================");

        // 2. Output to console
        $this->newLine();
        $this->info("=================================================================");
        $this->info("  ACTIVATION LINK LOGGED TO LARAVEL LOG");
        $this->info("=================================================================");
        $this->line("  <comment>Email:</comment>            {$user->email}");
        $this->line("  <comment>Username:</comment>         {$user->username}");
        $this->line("  <comment>Full Name:</comment>        {$fullName}");
        $this->line("  <comment>Activation Token:</comment> <info>{$token}</info>");
        $this->line("  <comment>Activation Link:</comment>  <href={$activationLink}>{$activationLink}</>");
        $this->line("  <comment>Expires:</comment>          {$expiresAt->toDateTimeString()} " . ($isExpired ? '<fg=red>(EXPIRED)</>' : '<fg=green>(VALID)</>'));
        $this->info("=================================================================");
        $this->comment("  Log file location: storage/logs/laravel.log");
        $this->comment("  Enter either the Token '{$token}' or the full link into the mobile app!");
        $this->newLine();

        return 0;
    }
}
