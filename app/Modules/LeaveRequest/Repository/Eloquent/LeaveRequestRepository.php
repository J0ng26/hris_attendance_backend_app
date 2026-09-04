<?php

namespace App\Modules\LeaveRequest\Repository\Eloquent;

use App\Models\LeaveRequest;
use App\Modules\BaseRepository;
use App\Modules\LeaveRequest\Repository\LeaveRequestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LeaveRequestRepository extends BaseRepository implements LeaveRequestRepositoryInterface
{
    public function __construct(LeaveRequest $model)
    {
        parent::__construct($model);
    }

    public function getByUserId(string $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('submitted_at', 'desc')
            ->get();
    }
}
