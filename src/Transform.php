<?php
declare(strict_types=1);

namespace ForestServer;

use ForestServer\Api\Request\RequestInterface;
use ForestServer\Attributes\UseParam;
use ReflectionClass;
use Swoole\Exception;

class Transform
{
    public function transformJsonToObject(string $json, string $class): RequestInterface
    {
        $objectClass = new $class();
        $reflectionClass = new ReflectionClass($objectClass);
        $data = json_decode($json, true);

        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                if ($attribute->getName() === UseParam::class) {
                    $property->setAccessible(true);
                    $property->setValue($objectClass, $this->getValueFromData($data, $property->getName()));
                }
            }
        }

        return $objectClass;
    }

    private function getValueFromData(array $data, string $key): string
    {
        return $data[$key] ?? throw new Exception('Property from your JSON not found in class');
    }
}
