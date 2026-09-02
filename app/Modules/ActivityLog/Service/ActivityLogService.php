<?php

namespace App\Modules\ActivityLog\Service;

use App\Modules\ActivityLog\Repository\ActivityLogRepositoryInterface;

class ActivityLogService
{
    private $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function all(?string $user_id, ?string $action, ?string $start_date, ?string $end_date)
    {
        return $this->activityLogRepository->all($user_id, $action, $start_date, $end_date);
    }

    public function getUserLastLoginDate(string $user_id): ?string
    {
        return $this->activityLogRepository->getUserLastLoginDate($user_id);
    }

    public function add(
        string $subject_type,
        string $action,
        ?string $user_id = null,
        array|string|null $before = null,
        array|string|null $after = null
    ) {
        return $this->activityLogRepository->create([
            'subject_type' => $subject_type,
            'action'       => $action,
            'user_id'      => $user_id,
            'changes'      => [
                'before' => $before,
                'after'  => $after,
            ],
        ]);
    }
}
