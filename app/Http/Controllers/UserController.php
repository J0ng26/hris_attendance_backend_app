<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\NonAdminUserResource;
use App\Modules\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $user = Auth::user();
        $userLevel = $user->user_type?->level;
        $department = $user->department?->name;
        $username = $user->username;

        $users = $this->userService->all($userLevel, $department, $username);

        return response()->json($users);
    }

    public function getUserNonAdmin()
    {
        $userData = $this->userService->getUserNonAdmin();

        return new NonAdminUserResource($userData);
    }

    public function add(UserRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userService->add(
                $validated['username'],
                $validated['first_name'],
                $validated['middle_name'] ?? null,
                $validated['last_name'],
                $validated['email'],
                $validated['active'],
                $validated['user_type_id'],
                $validated['department_id'] ?? null
            );
        });

        return $returnValue;
    }

    public function edit(UserRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userService->edit(
                $validated['id'],
                $validated['username'],
                $validated['first_name'],
                $validated['middle_name'] ?? null,
                $validated['last_name'],
                $validated['email'],
                $validated['active'],
                $validated['user_type_id'],
                $validated['department_id'] ?? null
            );
        });

        return $returnValue;
    }

    public function updateProfile(UserRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $returnValue = DB::transaction(function () use ($validated, $user) {
            return $this->userService->edit(
                $user->id,
                $user->username,
                $validated['first_name'],
                $validated['middle_name'] ?? null,
                $validated['last_name'],
                $validated['email'],
                $user->active,
                $user->user_type_id,
                $user->department_id
            );
        });
        return $returnValue;
    }

    public function delete(UserRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->userService->delete($validated['id']);
        });

        return $returnValue;
    }
}
