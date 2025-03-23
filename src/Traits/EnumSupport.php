<?php

namespace App\Traits;

trait EnumSupport
{
    public static function toArray(?string $prop = null): array
    {
        $keys = self::values();

        if ($prop) {
            $values = array_map(fn (self $case) => self::values(call_user_func([$case, $prop])), self::cases());
        }

        return array_combine($keys, $values ?? $keys);
    }

    public static function names(?array $cases = null): array
    {
        return array_column($cases ?: self::cases(), 'name');
    }

    public static function values(?array $cases = null): array
    {
        return array_column($cases ?: self::cases(), 'value');
    }
}
