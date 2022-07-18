<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserRole;

class RoleCheck
{
    /**
     * @param User $user
     * @param string $role
     * @return bool|mixed
     */
    public function check(User $user, string $role)
    {
        // Admin has everything
        if ($user->hasRole(UserRole::ROLE_ROOT)) {
            return true;
        } else if ($user->hasRole(UserRole::ROLE_ADMIN)) {
            $adminRoles = UserRole::getAllowedRoles(UserRole::ROLE_ADMIN);

            if (in_array($role, $adminRoles)) {
                return true;
            }
        }

        return $user->hasRole($role);
    }
}
