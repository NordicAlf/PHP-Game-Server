<?php
declare(strict_types=1);

namespace ForestServer\Service\Utils\Trait;

use ForestServer\Service\Converter\StringService;

trait ObjectTrait
{
    public function toArray(): array
    {
        foreach ($this as $propertyName => $propertyValue) {
            $array[StringService::convertCamelToSnakeCase($propertyName)] = $propertyValue;
        }

        return $array ?? [];
    }
}
