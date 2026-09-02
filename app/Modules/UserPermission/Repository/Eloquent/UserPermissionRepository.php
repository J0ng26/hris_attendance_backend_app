<?php


namespace App\Modules\UserPermission\Repository\Eloquent;


use App\Models\Permission;
use App\Modules\BaseRepository;
use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Collection;

class UserPermissionRepository extends BaseRepository implements UserPermissionRepositoryInterface
{
    /**
     * PermissionRepository constructor.
     *
     * @param UserPermission $model
     */
    public function __construct(UserPermission $model)
    {
        // Use the UserPermission(model) class
        // Pass that model
        parent::__construct($model);
    }

    public function manage(string $userId, array $permissionIds)
    {
        $validPermissionIds = Permission::whereIn('id', $permissionIds)->pluck('id')->toArray();

        $user = User::findOrFail($userId);

        $user->permissions()->sync($validPermissionIds);

        return true;
    }

    public function getPermissionsByUserId(string $userId)
    {
        return $this->model->with('permission')
        ->where('user_id', $userId)->get();
    }

    public function getAllUsersAndPermissions(): Collection
    {
        return User::whereHas('permissions')
            ->with('permissions')
            ->get();
    }
}
