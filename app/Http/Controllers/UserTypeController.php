<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTypeRequest;
use App\Http\Resources\UserTypePermissionCollection;
use App\Modules\ActivityLog\Service\ActivityLogService;
use App\Modules\UserType\Service\UserTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserTypeController extends Controller
{
    private $userTypeService;
    private $activityLogService;

    public function __construct(UserTypeService $userTypeService, ActivityLogService $activityLogService)
    {
        // Get the Interface and let it decide what implementor to use via provider
        $this->userTypeService = $userTypeService;
        $this->activityLogService = $activityLogService;
    }

    public function all(UserTypeRequest $request)
    {
        $validated = $request->validated();
        $userType = $this->userTypeService->getAllUserTypes($validated['department_id']);
        return new UserTypePermissionCollection($userType);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $currentRole = $user->userTypeEnum();
        Log::info('Current user role: ' . ($currentRole ? $currentRole->name : 'None'));

        if (!$currentRole) {
            return response()->json([], 403);
        }

        $userTypes = $this->userTypeService->allExceptHigherPosition($currentRole);

        return response()->json($userTypes);
    }

    public function create(UserTypeRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userTypeService->add(
                $validated['name']
            );
        });

        return $returnValue;
    }

    public function update(UserTypeRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userTypeService->edit(
                $validated['id'],
                $validated['name']
            );
        });

        return $returnValue;
    }

    public function delete(UserTypeRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userTypeService->delete(
                $validated['id']
            );
        });

        return $returnValue;
    }
}
