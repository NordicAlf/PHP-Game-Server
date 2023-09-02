<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Enum\RequestTypeEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Service\Trait\ObjectTrait;

class GameRequest extends AbstractRequest implements RequestInterface
{
    use ObjectTrait;

    public function getType(): RequestTypeEnum
    {
        return RequestTypeEnum::Game;
    }
}
