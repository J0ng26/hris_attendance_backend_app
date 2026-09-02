<?php

namespace App\Http\Middleware;

use App\Modules\AppSettings\Service\AppSettingsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRouteLockMiddleware
{
    protected $appSettingsService;

    public function __construct(AppSettingsService $appSettingsService)
    {
        $this->appSettingsService = $appSettingsService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if api is locked on the app settings table
        // Only Super admin can access all api
        if($this->appSettingsService->getApiStatus()){
            $userInfo = $request->user();

            if($userInfo->user_type->level == 100){
                return $next($request);
            }
            else{
                return response()->json(['message' => 'This app is in maintenance mode! All request will be declined!.'], 403);
            }
        }
        else{
            return $next($request);
        }
    }
}
