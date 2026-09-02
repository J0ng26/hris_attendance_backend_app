<?php

namespace App\Modules\Department\Repository\Eloquent;

use App\Models\Department;
use App\Modules\BaseRepository;
use App\Modules\Department\Repository\DepartmentRepositoryInterface;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }
}
