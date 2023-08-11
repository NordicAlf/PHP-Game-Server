<?php
declare(strict_types=1);

namespace ForestServer;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ReflectionClass;
use Swoole\Exception;

class Transform
{
    public static function transformJsonToObject(array $json, string $class): RequestInterface
    {
        $objectClass = new $class();
        $reflectionClass = new ReflectionClass($objectClass);

        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                if ($attribute->getName() === UseParam::class) {
                    $property->setAccessible(true);
                    $property->setValue($objectClass, self::getValueFromData($json, $property->getName()));
                }
            }
        }

        return $objectClass;
    }

    private static function getValueFromData(array $data, string $key): string
    {
        return $data[$key] ?? throw new Exception('Property from your JSON not found in class');
    }
}
