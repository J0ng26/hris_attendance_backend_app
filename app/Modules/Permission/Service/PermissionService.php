<?php

namespace App\Modules\Permission\Service;

use App\Modules\Permission\Repository\PermissionRepositoryInterface;
use App\Enums\PermissionKey;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllTitlePermissions()
    {
        return $this->permissionRepository->index();
    }

    public function createPermissions(string $title)
    {
        $base = $this->getBase($title);
        $actions = PermissionKey::values();
        $created = [];

        foreach ($actions as $action) {
            $permissionKey = $base . '.' . $action;
            $name = $this->getDisplayName($title);

            $permission = $this->permissionRepository->getByPermissionKey($permissionKey);

            if ($permission) {
                if ($permission->name !== $name) {
                    return response()->json([
                        'message' => 'Permission key already exists with different name.',
                    ], 400);
                }

                $created[] = $permission;
                continue;
            }

            $created[] = $this->permissionRepository->create([
                'name' => $name,
                'permission_key' => $permissionKey
            ]);
        }

        return response()->json([
            'message' => 'Permissions created successfully.',
            'permission' => $created
        ], 201);
    }

    public function createPermissionKey(string $title, string $key)
    {
        $permission = $this->permissionRepository->getByPermissionKey($key);

        if ($permission) {
            return response()->json([
                'message' => 'Permission key already exists.',
            ], 400);
        }

        $created = $this->permissionRepository->create([
            'name' => $title,
            'permission_key' => $key
        ]);

        return response()->json([
            'message' => 'Permission created successfully.',
            'permission' => $created
        ], 201);
    }

    public function updatePermissionKeyByTitle(string $oldTitle, string $newTitle)
    {
        $oldBase = $this->getBase($oldTitle);
        $newBase = $this->getBase($newTitle);
        $actions = PermissionKey::values();
        $result = [];

        foreach ($actions as $action) {
            $oldKey = $oldBase . '.' . $action;
            $newKey = $newBase . '.' . $action;
            $newName = $this->getDisplayName($newTitle);

            $permission = $this->permissionRepository->getByPermissionKey($oldKey);

            if ($permission && $permission->name === $newName) {
                $result[] = $permission;
                continue;
            } else if ($permission) {
                return response()->json([
                    'message' => 'Permission key already exists with different name.',
                ], 400);
            }

            if ($permission) {
                $updated = $this->permissionRepository->update($permission->id, [
                    'permission_key' => $newKey,
                    'name' => $newName,
                ]);
                $result[] = $updated;
            } else {
                $created = $this->permissionRepository->create([
                    'permission_key' => $newKey,
                    'name' => $newName,
                ]);
                $result[] = $created;
            }
        }

        return response()->json([
            'message' => 'Permissions updated successfully.',
            'permission' => $result
        ], 200);
    }

    public function updatePermissionKey(string $id, string $key)
    {
        $permission = $this->permissionRepository->findByPrimaryId($id);

        if (!$permission) {
            return response()->json([
                'message' => 'Permission not found.',
            ], 404);
        }

        if ($permission->permission_key === $key) {
            return response()->json([
                'message' => 'Permission key is the same as the current key.',
                'permission' => $permission
            ], 200);
        }

        $existingPermission = $this->permissionRepository->getByPermissionKey($key);

        if ($existingPermission) {
            return response()->json([
                'message' => 'Permission key already exists.',
            ], 400);
        }

        $updated = $this->permissionRepository->update($id, [
            'permission_key' => $key,
        ]);

        return response()->json([
            'message' => 'Permission key updated successfully.',
            'permission' => $updated
        ], 200);
    }

    public function deletePermissionKeyByTitle(string $title)
    {
        $base = $this->getBase($title);
        $actions = PermissionKey::values();
        $deleted = [];

        foreach ($actions as $action) {
            $permissionKey = $base . '.' . $action;
            $permission = $this->permissionRepository->getByPermissionKey($permissionKey);

            if ($permission) {
                $this->permissionRepository->delete($permission->id);
                $deleted[] = $permissionKey;
            }
        }

        return response()->json([
            'message' => 'Permission ' . $title . ' deleted successfully.',
            'permissions' => $deleted
        ], 200);
    }

    public function deletePermissionKey(string $id)
    {
        $permission = $this->permissionRepository->findByPrimaryId($id);

        if (!$permission) {
            return response()->json([
                'message' => 'Permission not found.',
            ], 404);
        }

        $this->permissionRepository->delete($id);

        return response()->json([
            'message' => 'Permission deleted successfully.',
            'permission' => $permission
        ], 200);
    }

    private function getBase(string $title): string
    {
        return Str::kebab(Str::singular($title));
    }

    private function getDisplayName(string $title): string
    {
        return Str::title(Str::singular(trim($title)));
    }
}
