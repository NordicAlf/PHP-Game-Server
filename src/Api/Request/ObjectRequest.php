<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ForestServer\Service\Utils\Trait\ObjectTrait;

class ObjectRequest extends AbstractRequest implements RequestInterface
{
    use ObjectTrait;

    #[UseParam]
    protected string $roomId;

    #[UseParam]
    protected string $objectId;

    public function getRoomId(): string
    {
        return $this->roomId;
    }

    public function getObjectId(): string
    {
        return $this->objectId;
    }
}
