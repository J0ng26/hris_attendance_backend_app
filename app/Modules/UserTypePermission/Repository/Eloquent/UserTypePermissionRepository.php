<?php


namespace App\Modules\UserTypePermission\Repository\Eloquent;

use App\Models\Department;
use App\Models\Permission;
use App\Modules\BaseRepository;
use App\Modules\UserTypePermission\Repository\UserTypePermissionRepositoryInterface;
use App\Models\UserType;
use App\Models\UserTypePermission;

class UserTypePermissionRepository extends BaseRepository implements UserTypePermissionRepositoryInterface
{
    /**
     * PermissionRepository constructor.
     *
     * @param UserTypePermission $model
     */
    public function __construct(UserTypePermission $model)
    {
        // Use the UserTypePermission(model) class
        // Pass that model
        parent::__construct($model);
    }

    public function manage(string $userTypeId, string $departmentId, array $permissionIds)
    {
        $validPermissionIds = Permission::whereIn('id', $permissionIds)
            ->pluck('id')
            ->toArray();

        UserType::findOrFail($userTypeId);
        Department::findOrFail($departmentId);

        UserTypePermission::where('user_type_id', $userTypeId)
            ->where('department_id', $departmentId)
            ->delete();

        $now = now();

        $rows = collect($validPermissionIds)->map(fn($permissionId) => [
            'user_type_id' => $userTypeId,
            'department_id' => $departmentId,
            'permission_id' => $permissionId,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        UserTypePermission::insert($rows);

        return true;
    }
}
