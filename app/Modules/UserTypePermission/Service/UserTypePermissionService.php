<?php

namespace App\Modules\UserTypePermission\Service;

use App\Modules\UserTypePermission\Repository\UserTypePermissionRepositoryInterface;

class UserTypePermissionService
{
    private $userTypePermissionRepository;

    public function __construct(UserTypePermissionRepositoryInterface $userTypePermissionRepository)
    {
        $this->userTypePermissionRepository = $userTypePermissionRepository;
    }

    public function manageUserTypePermission(string $userTypeId, string $departmentId, array $permissionIds)
    {
        return $this->userTypePermissionRepository->manage($userTypeId, $departmentId, $permissionIds);
    }
}