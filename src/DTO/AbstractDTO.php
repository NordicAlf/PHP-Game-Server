<?php
declare(strict_types=1);

namespace ForestServer\DTO;

use ForestServer\Service\StringService;

abstract class AbstractDTO
{
    public function toArray(): array
    {
        foreach ($this as $propertyName => $propertyValue) {
            $array[StringService::convertCamelToSnakeCase($propertyName)] = $propertyValue;
        }

        return $array ?? [];
    }
}
