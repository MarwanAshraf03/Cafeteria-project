<?php
namespace App\Enums;

class Role
{
    public const Admin = 'ADMIN';
    public const User = 'USER';

    public static function tryFrom($value)
    {
        $upper = strtoupper((string) $value);

        if ($upper === self::Admin) {
            return self::Admin;
        }

        if ($upper === self::User) {
            return self::User;
        }

        return null;
    }
}
?>