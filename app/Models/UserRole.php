<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole
{
    const ROLE_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_CLIENT = 'client';
    const ROLE_CONSULTATION = 'consultation';
    const ROLE_AUXILIAIRE = 'auxiliaire';

    /**
     * @var array
     */
    protected static $roleHierarchy = [
        self::ROLE_ROOT   => ['*'],
        self::ROLE_ADMIN  => [
            self::ROLE_USER,
            //self::ROLE_CLIENT,
        ],

        self::ROLE_USER   => [],
        self::ROLE_CLIENT => [],
        self::ROLE_CONSULTATION => [],
        self::ROLE_AUXILIAIRE => []
    ];

    /**
     * @param string $role
     * @return array
     */
    public static function getAllowedRoles(string $role)
    {
        if (isset(self::$roleHierarchy[$role])) {
            return self::$roleHierarchy[$role];
        }

        return [];
    }

    /***
     * @return array
     */
    public static function getRoleList()
    {
        return [
            static::ROLE_ROOT         => 'Root',
            static::ROLE_ADMIN        => 'Admin',
            static::ROLE_USER         => 'User',
            static::ROLE_CLIENT       => 'Client',
            static::ROLE_CONSULTATION => 'Consultation',
            static::ROLE_AUXILIAIRE   => 'Auxiliaire'
        ];
    }

     /***
     * @return array
     */
    public static function getRoleAdminList()
    {
        return [
            static::ROLE_ADMIN  => 'Admin',
            static::ROLE_USER   => 'User',
        ];
    }
}
