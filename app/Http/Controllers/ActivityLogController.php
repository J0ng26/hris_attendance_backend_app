<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityLogRequest;
use App\Modules\ActivityLog\Service\ActivityLogService;

class ActivityLogController extends Controller
{

    private $activityLogService;

    public function __construct(
        ActivityLogService $activityLogService
    ) {
        $this->activityLogService = $activityLogService;
    }

    public function all(ActivityLogRequest $request)
    {
        $validated = $request->validated();
        return $this->activityLogService->all(
            $validated['user_id'] ?? null,
            $validated['action'] ?? null,
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null,
        );
    }
}
