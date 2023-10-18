<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ForestServer\Service\Utils\Trait\ObjectTrait;

class RoomUpdateRequest extends AbstractRequest implements RequestInterface
{
    use ObjectTrait;

    #[UseParam]
    protected string $roomId;

    public function getRoomId(): string
    {
        return $this->roomId;
    }
}
