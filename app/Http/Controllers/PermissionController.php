<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Modules\Permission\Service\PermissionService;
use App\Modules\UserPermission\Service\UserPermissionService;
use App\Modules\UserTypePermission\Service\UserTypePermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    private $permissionService;
    private $userTypePermissionService;
    private $userPermissionService;

    public function __construct(PermissionService $permissionService, UserTypePermissionService $userTypePermissionService, UserPermissionService $userPermissionService)
    {
        $this->permissionService = $permissionService;
        $this->userTypePermissionService = $userTypePermissionService;
        $this->userPermissionService = $userPermissionService;
    }

    public function index(Request $request)
    {
        $permission = $this->permissionService->getAllTitlePermissions();
        return new PermissionCollection($permission);
    }

    public function all(Request $request)
    {
        return $this->permissionService->getAllTitlePermissions();
    }

    public function create(PermissionRequest $request)
    {
        $validated = $request->validated();
        $returnValue = DB::transaction(function () use ($validated) {
            return $this->permissionService->createPermissions($validated['title']);
        });

        return $returnValue;
    }

    public function createKey(PermissionRequest $request)
    {
        $validated = $request->validated();
        $returnValue = DB::transaction(function () use ($validated) {
            return $this->permissionService->createPermissionKey(
                $validated['title'],
                $validated['key']
            );
        });

        return $returnValue;
    }

    public function update(PermissionRequest $request)
    {
        $validated = $request->validated();
        $returnValue = DB::transaction(function () use ($validated) {
            return $this->permissionService->updatePermissionKeyByTitle(
                $validated['oldTitle'],
                $validated['newTitle']
            );
        });

        return $returnValue;
    }

    public function updateKey(PermissionRequest $request)
    {
        $validated = $request->validated();
        $returnValue = DB::transaction(function () use ($validated) {
            return $this->permissionService->updatePermissionKey(
                $validated['id'],
                $validated['key']
            );
        });

        return $returnValue;
    }

    public function delete(PermissionRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->permissionService->deletePermissionKeyByTitle(
                $validated['title']
            );
        });

        return $returnValue;
    }

    public function deleteKey(PermissionRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->permissionService->deletePermissionKey(
                $validated['id']
            );
        });

        return $returnValue;
    }

    public function manage(PermissionRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userTypePermissionService->manageUserTypePermission(
                $validated['user_type_id'],
                $validated['department_id'],
                $validated['permissions']
            );
        });

        return $returnValue;
    }

    public function manageSpecialUserAccess(PermissionRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userPermissionService->manageUserPermission(
                $validated['user_id'],
                $validated['permissions']
            );
        });

        return $returnValue;
    }

    public function getAllUsersAndPermissions()
    {
        return $this->userPermissionService->getAllUsersAndPermissions();
    }
}
