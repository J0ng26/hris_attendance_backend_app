<?php

namespace App\Modules\LeaveRequest\Repository;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface LeaveRequestRepositoryInterface extends EloquentRepositoryInterface
{
    public function getByUserId(string $userId): Collection;
}
