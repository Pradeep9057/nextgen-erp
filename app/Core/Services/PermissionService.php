<?php

namespace App\Core\Services;

use App\Core\Models\Role;
use App\Core\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Exception;

class PermissionService
{
    /**
     * Check if the current user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        // Super Admin bypass
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->permissions()->where('slug', $permissionSlug)->exists();
    }

    public function assignRoleToUser(int $userId, int $roleId, ?int $branchId = null): void
    {
        \DB::table('user_role')->updateOrInsert([
            'user_id' => $userId,
            'role_id' => $roleId,
            'branch_id' => $branchId
        ]);
    }

    public function syncRolePermissions(int $roleId, array $permissionIds): void
    {
        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($permissionIds);
    }
}
