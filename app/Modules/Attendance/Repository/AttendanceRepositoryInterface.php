<?php

namespace App\Modules\Attendance\Repository;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AttendanceRepositoryInterface extends EloquentRepositoryInterface
{
    public function getByUserId(string $userId): Collection;
    public function getTodayByUserId(string $userId): Collection;
    public function getLatestByUserId(string $userId);
}
