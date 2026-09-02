<?php


namespace App\Modules\UserPermission\Repository;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface UserPermissionRepositoryInterface extends EloquentRepositoryInterface
{
    public function manage(string $userTypeId, array $permissionIds);

    public function getPermissionsByUserId(string $userId);

    public function getAllUsersAndPermissions(): Collection;
}
