<?php

namespace App\Modules\UserType\Service;

use App\Modules\ActivityLog\Service\ActivityLogService;
use App\Modules\UserType\Repository\UserTypeRepositoryInterface;
use Illuminate\Support\Facades\Response;

class UserTypeService
{
    private $userTypeRepository;

    public function __construct(UserTypeRepositoryInterface $userTypeRepository)
    {
        // Get the Interface and let it decide what implementor to use via provider
        $this->userTypeRepository = $userTypeRepository;
    }

    protected function getActivityLogService(): ActivityLogService
    {
        return app(ActivityLogService::class);
    }

    public function getAllUserTypes(string $departmentId)
    {
        return $this->userTypeRepository->all($departmentId);
    }

    public function allExceptHigherPosition(int $userLevel)
    {
        return $this->userTypeRepository->allExceptHigherPosition($userLevel);
    }

    public function getUserTypeWithPermissions(string $id)
    {
        return $this->userTypeRepository->getUserTypeWithPermissions($id);
    }

    public function add(string $name, int $level, int $userLevel)
    {
        if ($level > $userLevel) {
            return Response::json([
                'message' => 'Cannot add a user type with a higher level than your own.'
            ], 403);
        }

        $user_type = $this->userTypeRepository->create([
            'name' => $name,
            'level' => $level
        ]);

        return Response::json([
            'message' => 'User type ' . $user_type->name . ' created successfully.',
            'user_type' => $user_type
        ], 201);
    }

    public function edit(string $id, string $name, int $level, int $userLevel)
    {
        if ($level > $userLevel) {
            return Response::json([
                'message' => 'Cannot edit a user type with a higher level than your own.'
            ], 403);
        }

        $user_type = $this->userTypeRepository->update($id, [
            'name' => $name,
            'level' => $level
        ]);

        return Response::json([
            'message' => 'User type ' . $user_type->name . ' updated successfully.',
            'user_type' => $user_type
        ], 200);
    }

    public function delete(string $id, int $userLevel)
    {
        if ($this->userTypeRepository->findByPrimaryId($id)->level > $userLevel) {
            return Response::json([
                'message' => 'Cannot delete a user type with a higher level than your own.'
            ], 403);
        }

        $userType = $this->userTypeRepository->findByPrimaryId($id);

        $this->userTypeRepository->delete($id);

        return Response::json([
            'message' => 'User type ' . $userType->description . ' deleted successfully.'
        ], 200);
    }
}
