<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPermissionMiddleware
{
	/**
	 * Handle an incoming request.
	 * Expected usage: ->middleware('permission:permission.key')
	 */
	public function handle(Request $request, Closure $next, ?string $permission = null)
	{
		$user = $request->user() ?? Auth::user();

		if (! $user) {
			return response()->json(['message' => 'Unauthenticated.'], 401);
		}

		$userTypeLevel = $user->user_type?->level;

		if (! $permission) {
			return $next($request);
		}

		if ($userTypeLevel == 100) {
			return $next($request);
		}

		$requested = preg_split('/[|,]/', $permission);

		$userTypeId = $user->user_type_id;
		$departmentId = $user->department_id;

		foreach ($requested as $permKey) {
			$permKey = trim($permKey);

			if ($permKey === '') {
				continue;
			}

			$hasUserPermission = DB::table('permissions')
				->join('user_permission', 'permissions.id', '=', 'user_permission.permission_id')
				->where('permissions.permission_key', $permKey)
				->where('user_permission.user_id', $user->id)
				->exists();

			if ($hasUserPermission) {
				return $next($request);
			}

			if (! $userTypeId || ! $departmentId) {
				continue;
			}

			$hasUserTypePermission = DB::table('permissions')
				->join('user_type_permissions', 'permissions.id', '=', 'user_type_permissions.permission_id')
				->where('permissions.permission_key', $permKey)
				->where('user_type_permissions.user_type_id', $userTypeId)
				->where('user_type_permissions.department_id', $departmentId)
				->exists();

			if ($hasUserTypePermission) {
				return $next($request);
			}
		}

		return response()->json([
			'message' => 'You do not have permission to access this resource.'
		], 403);
	}
}
