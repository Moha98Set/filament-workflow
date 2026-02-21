<?php

namespace App\Helpers;

use Hekmatinasser\Verta\Verta;

class JalaliHelper
{
    public static function toJalali($date, string $format = 'Y/m/d'): string
    {
        if (!$date) return '—';
        try {
            return (new Verta($date))->format($format);
        } catch (\Exception $e) {
            return '—';
        }
    }

    public static function toJalaliDateTime($date): string
    {
        return self::toJalali($date, 'Y/m/d H:i');
    }
}