<?php

namespace App\Modules\ActivityLog\Repository\Eloquent;

use App\Models\ActivityLog;
use App\Modules\ActivityLog\Repository\ActivityLogRepositoryInterface;
use App\Modules\BaseRepository;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    public function getUserLastLoginDate(string $user_id): ?string
    {
        $activity = $this->model
            ->where('user_id', $user_id)
            ->where('action', 'authenticated')
            ->latest('created_at')
            ->first();

        return $activity?->created_at?->format('Y-m-d H:i:s');
    }

    public function all(?string $user_id, ?string $action, ?string $start_date, ?string $end_date)
    {
        $query = $this->model->newQuery();
        $query->with(['user:id,first_name,last_name,email']);
        $query->orderBy('created_at', 'desc');

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($start_date) {
            $query->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('created_at', '<=', $end_date);
        }

        return $query->get();
    }
}
