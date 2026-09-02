<?php


namespace App\Modules\UserTypePermission\Repository;

use App\Modules\EloquentRepositoryInterface;

interface UserTypePermissionRepositoryInterface extends EloquentRepositoryInterface
{
    public function manage(string $userTypeId, string $departmentId, array $permissionIds);
}
