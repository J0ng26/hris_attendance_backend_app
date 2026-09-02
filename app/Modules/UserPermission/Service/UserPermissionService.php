<?php

namespace App\Modules\UserPermission\Service;

use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;

class UserPermissionService
{
    private $userPermissionRepository;

    public function __construct(UserPermissionRepositoryInterface $userPermissionRepository)
    {
        $this->userPermissionRepository = $userPermissionRepository;
    }

    public function manageUserPermission(string $userId, array $permissionIds)
    {
        return $this->userPermissionRepository->manage($userId, $permissionIds);
    }

    public function getAllUsersAndPermissions()
    {
        return $this->userPermissionRepository->getAllUsersAndPermissions();
    }
}
