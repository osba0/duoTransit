<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Helpers\RoleCheck as UserRoleCheckHelper;
use Auth;

class RoleCheck
{
    /**
     * @var UserRoleCheckHelper
     */
    protected $roleCheck;

    /**
     * RoleCheck constructor.
     * @param UserRoleCheckHelper $roleCheck
     */
    public function __construct(UserRoleCheckHelper $roleCheck)
    {
        $this->roleCheck = $roleCheck;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::guard()->user();

       /* if ( ! $this->roleCheck->check($user, $role)) {
            throw new AuthorizationException('You do not have permission.');
        }

        return $next($request);*/
        // Admin has everything
        /*if ($user->hasRole(UserRole::ROLE_ROOT)) {
            return true;
        } else if ($user->hasRole(UserRole::ROLE_ADMIN)) {
            $managementRoles = UserRole::getAllowedRoles(UserRole::ROLE_ADMIN);

            if (in_array($role, $managementRoles)) {
                return true;
            }
        }

        return $user->hasRole($role);*/
        if ( ! $this->roleCheck->check($user, $role)) {
            throw new AuthorizationException('You do not have permission.');
        }

        return $next($request);
    }
}
