<?php
declare(strict_types=1);

namespace ForestServer\Service\Container;

use DI\Container;

class ServiceContainer
{
    private static Container $container;

    public static function set(Container $container): void
    {
        self::$container = $container;
    }

    public static function getContainer(): Container
    {
        return self::$container;
    }
}
