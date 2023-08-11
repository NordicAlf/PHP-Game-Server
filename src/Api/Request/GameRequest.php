<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Enum\RequestTypeEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\DTO\AbstractDTO;

class GameRequest extends AbstractDTO implements RequestInterface
{
    public function getType(): RequestTypeEnum
    {
        return RequestTypeEnum::Game;
    }
}
