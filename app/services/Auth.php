<?php

namespace App\Services;

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../enums/Role.php';
use App\Enums\Role;
use App\Models\User;

class Auth
{
    private static $user = null;

    public static function user()
    {
        if (self::$user === null && isset($_SESSION['userId'])) {
            self::$user = User::find($_SESSION['userId']);
        }
        return self::$user;
    }

    public static function role()
    {
        return Role::tryFrom(self::user()->role);
    }

    public static function check()
    {
        return isset($_SESSION['userId']);
    }

    public static function login($userId)
    {
        $_SESSION['userId'] = $userId;
    }

    public static function logout()
    {
        unset($_SESSION['userId']);
        self::$user = null;
    }
}
