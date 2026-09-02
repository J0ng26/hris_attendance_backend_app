<?php

namespace App\Http\Controllers;

use App\Modules\AppSettings\Service\AppSettingsService;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    private $appSettingsService;

    public function __construct(AppSettingsService $appSettingsService)
    {
        // Get the Interface and let it decide what implementor to use via provider
        $this->appSettingsService = $appSettingsService;
    }

    public function lockApi(Request $request)
    {
        $user = $request->user();
        if($user->user_type->level < 100){
            return response([
                'message' => 'User is not allowed for this action!'
            ], 401);
        }

        return $this->appSettingsService->lockApi($user->username, $user->id);
    }

    public function unlockApi(Request $request)
    {
        $user = $request->user();
        if($user->user_type->level < 100){
            return response([
                'message' => 'User is not allowed for this action!'
            ], 401);
        }

        return $this->appSettingsService->unlockApi($user->username, $user->id);
    }
}
