<?php

namespace App\Core\Policies;

use App\Models\User;
use App\Core\Services\PermissionService;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function __construct(protected PermissionService $permissionService) {}

    protected function check(string $permission): bool
    {
        return $this->permissionService->hasPermission($permission);
    }
}
