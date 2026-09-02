<?php

namespace App\Modules\User\Repository\Eloquent;

use App\Models\User;
use App\Modules\BaseRepository;
use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $username
     * @param array $relations
     * @return User
     */
    public function findByUsername(string $username, array $relations = []): ?User
    {
        $finalQuery = $this->model->query();
        $finalQuery = $finalQuery->where('username', $username);
        $finalQuery = $finalQuery->with($relations);
        $finalQuery = $finalQuery->first();

        return $finalQuery;
    }

    /**
     * @param string $email
     * @param array $relations
     * @return User
     */
    public function findByEmail(string $email, array $relations = []): ?User
    {
        $finalQuery = $this->model->query();
        $finalQuery = $finalQuery->where('email', $email);
        $finalQuery = $finalQuery->with($relations);
        $finalQuery = $finalQuery->first();

        return $finalQuery;
    }

    /**
     * @param string $username
     * @param string $password
     * @return int
     */
    public function resetPassword(string $username, string $password)
    {
        return $this->model->where('username', $username)->update([
            'password' => $password,
            'first_use' => false
        ]);
    }
}
