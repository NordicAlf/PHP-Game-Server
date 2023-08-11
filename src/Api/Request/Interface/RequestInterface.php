<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Interface;

use ForestServer\Api\Request\Enum\RequestTypeEnum;

interface RequestInterface
{
    public function getType(): RequestTypeEnum;
    public function toArray(): array;
}
