<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ForestServer\Service\Utils\Trait\ObjectTrait;

class RoomRequest extends AbstractRequest implements RequestInterface
{
    use ObjectTrait;

    #[UseParam]
    protected string $roomPassword;

    public function getPassword(): string
    {
        return $this->roomPassword;
    }
}
