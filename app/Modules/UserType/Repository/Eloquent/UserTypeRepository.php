<?php


namespace App\Modules\UserType\Repository\Eloquent;


use App\Models\UserType;
use App\Modules\BaseRepository;
use App\Modules\UserType\Repository\UserTypeRepositoryInterface;
use Illuminate\Support\Collection;

class UserTypeRepository extends BaseRepository implements UserTypeRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param UserType $model
     */
    public function __construct(UserType $model)
    {
        // Use the User(model) class
        // Pass that model
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(string $departmentId): Collection
    {
        return $this->model
            ->with([
                'permissions' => function ($query) use ($departmentId) {
                    $query->wherePivot('department_id', $departmentId);
                },
            ])
            ->where('level', '<', 100)
            ->orderBy('level', 'asc')
            ->get();
    }

    /**
     * @return Collection
     */
    public function allExceptHigherPosition(int $userLevel): Collection
    {
        return $this->model
            ->where('level', '<=', $userLevel)
            ->orderBy('level', 'asc')
            ->get();
    }

    public function getUserTypeWithPermissions(string $id): ?UserType
    {
        return $this->model->with(['permissions'])->find($id);
    }
}
