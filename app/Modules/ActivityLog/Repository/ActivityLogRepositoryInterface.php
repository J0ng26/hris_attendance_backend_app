<?php

namespace App\Modules\ActivityLog\Repository;

use App\Modules\EloquentRepositoryInterface;

interface ActivityLogRepositoryInterface extends EloquentRepositoryInterface
{
    public function getUserLastLoginDate(string $user_id): ?string;
    public function all(?string $user_id, ?string $action, ?string $start_date, ?string $end_date);
}
