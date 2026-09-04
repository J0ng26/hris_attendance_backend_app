<?php

namespace App\Http\Controllers;

use App\Modules\LeaveRequest\Service\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    private LeaveRequestService $leaveRequestService;

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        return $this->leaveRequestService->submit($request->user(), $validated);
    }

    public function myRequests(Request $request)
    {
        return $this->leaveRequestService->myRequests($request->user());
    }
}
