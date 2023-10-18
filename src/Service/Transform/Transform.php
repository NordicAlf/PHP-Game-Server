<?php
declare(strict_types=1);

namespace ForestServer\Service\Transform;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ForestServer\Attributes\UseRelation;
use ForestServer\DB\Entity\EntityInterface;
use ForestServer\DB\Repository\RepositoryInterface;
use ReflectionClass;
use Swoole\Exception;

class Transform
{
    public static function transformArrayToObject(array $array, string $class): RequestInterface | EntityInterface
    {
        $objectClass = new $class();
        $reflectionClass = new ReflectionClass($objectClass);

        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                if ($attribute->getName() === UseRelation::class) {
                    $entities = [];
                    $idsEntities = json_decode(self::getValueFromData($array, $property->getName()));

                    /** @var RepositoryInterface $repository */
                    $repository = new ($attribute->getArguments()['repository']);

                    foreach ($idsEntities as $entityId) {
                        $entities[] = $repository->getById($entityId);
                    }

                    $property->setAccessible(true);
                    $property->setValue($objectClass, $entities);
                }

                if ($attribute->getName() === UseParam::class) {
                    $property->setAccessible(true);
                    $property->setValue($objectClass, self::getValueFromData($array, $property->getName()));
                }
            }
        }

        return $objectClass;
    }

    private static function getValueFromData(array $data, string $key): string|int|array
    {
        return $data[$key] ?? throw new Exception("Property '$key' from your class not found in data");
    }
}
