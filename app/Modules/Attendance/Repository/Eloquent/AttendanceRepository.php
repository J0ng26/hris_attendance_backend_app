<?php

namespace App\Modules\Attendance\Repository\Eloquent;

use App\Models\AttendanceRecord;
use App\Modules\BaseRepository;
use App\Modules\Attendance\Repository\AttendanceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRepository extends BaseRepository implements AttendanceRepositoryInterface
{
    public function __construct(AttendanceRecord $model)
    {
        parent::__construct($model);
    }

    public function getByUserId(string $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('timestamp', 'desc')
            ->get();
    }

    public function getTodayByUserId(string $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereDate('timestamp', now()->toDateString())
            ->orderBy('timestamp', 'desc')
            ->get();
    }

    public function getLatestByUserId(string $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('timestamp', 'desc')
            ->first();
    }
}
