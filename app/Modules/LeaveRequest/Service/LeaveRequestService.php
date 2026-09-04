<?php

namespace App\Modules\LeaveRequest\Service;

use App\Models\User;
use App\Modules\LeaveRequest\Repository\LeaveRequestRepositoryInterface;
use Illuminate\Support\Facades\Response;

class LeaveRequestService
{
    private LeaveRequestRepositoryInterface $leaveRequestRepository;

    public function __construct(LeaveRequestRepositoryInterface $leaveRequestRepository)
    {
        $this->leaveRequestRepository = $leaveRequestRepository;
    }

    public function submit(User $user, array $data)
    {
        $leaveRequest = $this->leaveRequestRepository->create([
            'user_id' => $user->id,
            'reason' => $data['reason'],
            'submitted_at' => \Carbon\Carbon::now('Asia/Manila'),
            'status' => 'pending',
        ]);

        $userName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        return Response::json([
            'id' => $leaveRequest->id,
            'userId' => $leaveRequest->user_id,
            'userName' => $userName,
            'reason' => $leaveRequest->reason,
            'submittedAt' => \Carbon\Carbon::parse($leaveRequest->submitted_at)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
            'status' => $leaveRequest->status,
        ], 201);
    }

    public function myRequests(User $user)
    {
        $requests = $this->leaveRequestRepository->getByUserId($user->id);
        $userName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        return Response::json(
            $requests->map(fn ($r) => [
                'id' => $r->id,
                'userId' => $r->user_id,
                'userName' => $userName,
                'reason' => $r->reason,
                'submittedAt' => \Carbon\Carbon::parse($r->submitted_at)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
                'status' => $r->status,
            ])
        );
    }
}
