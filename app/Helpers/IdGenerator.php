<?php

namespace App\Helpers;

use App\Models\User;

class IdGenerator
{
    const PREFIX = 'S'; // S untuk Silok

    private static function generate($role, $prefix)
    {
        $lastUser = User::where('role', $role)
            ->where('id', 'LIKE', self::PREFIX . $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastUser) {
            $prefixLength = strlen(self::PREFIX . $prefix);
            $lastNumber = intval(substr($lastUser->id, $prefixLength));
            $newNumber = $lastNumber + 1;
            return self::PREFIX . $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return self::PREFIX . $prefix . '0001';
    }

    public static function generateUserId()
    {
        return self::generate('user', 'U');
    }

    public static function generateAdminId()
    {
        return self::generate('admin', 'A');
    }

    public static function generateFrontOfficeId()
    {
        return self::generate('Front Office', 'FO');
    }

    public static function generateBackOfficeId()
    {
        return self::generate('Back Office', 'BO');
    }

    public static function generateOperatorId()
    {
        return self::generate('Operator', 'O');
    }
    public static function generateKetuaRTId()
    {
        return self::generate('Ketua RT', 'RT');
    }
    public static function generateKetuaRWId()
    {
        return self::generate('Ketua RW', 'RW');
    }
    public static function generateLurahId()
    {
        return self::generate('Lurah', 'L');
    }
    public static function generateCamatId()
    {
        return self::generate('Camat', 'C');
    }

    public static function generateId($role)
    {
        $prefixes = [
            'user' => 'U',
            'admin' => 'A',
            'Front Office' => 'FO',
            'Back Office' => 'BO',
            'Operator' => 'O',
            'Ketua RT' => 'RT',
            'Ketua RW' => 'RW',
            'Lurah' => 'L',
            'Camat' => 'C',

        ];

        if (!isset($prefixes[$role])) {
            throw new \InvalidArgumentException("Role tidak valid");
        }

        return self::generate($role, $prefixes[$role]);
    }
}
