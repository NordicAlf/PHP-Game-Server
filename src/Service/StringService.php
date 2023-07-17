<?php
declare(strict_types=1);

namespace ForestServer\Service;

class StringService
{
    public static function convertCamelToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}
