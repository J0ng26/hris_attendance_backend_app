<?php

namespace App\Http\Controllers;

use App\Modules\Attendance\Service\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function record(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:logIn,logOut',
            'timestamp' => 'nullable|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|file|image|max:10240',
            'photo_path' => 'nullable|string',
        ]);

        return $this->attendanceService->record($request->user(), $validated, $request);
    }

    public function myRecords(Request $request)
    {
        return $this->attendanceService->myRecords($request->user());
    }

    public function today(Request $request)
    {
        return $this->attendanceService->today($request->user());
    }

    public function status(Request $request)
    {
        return $this->attendanceService->status($request->user());
    }
}
