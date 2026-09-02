<?php


namespace App\Modules\Permission\Repository\Eloquent;


use App\Models\Permission;
use App\Modules\BaseRepository;
use App\Modules\Permission\Repository\PermissionRepositoryInterface;
use Illuminate\Support\Collection;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function getByPermissionKey(string $key): ?Permission
    {
        return $this->model->where('permission_key', $key)->first();
    }
}
