<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Enum\RequestTypeEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ForestServer\Service\Trait\ObjectTrait;

class RoomRequest extends AbstractRequest implements RequestInterface
{
    use ObjectTrait;

    #[UseParam]
    protected string $roomAction;

    #[UseParam]
    protected string $roomPassword;

    public function getAction(): string
    {
        return $this->roomAction;
    }

    public function getPassword(): string
    {
        return $this->roomPassword;
    }

    public function getType(): RequestTypeEnum
    {
        return RequestTypeEnum::Room;
    }
}
