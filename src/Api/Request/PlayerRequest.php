<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Attributes\UseParam;
use ForestServer\Service\Utils\Trait\ObjectTrait;

class PlayerRequest extends AbstractRequest implements RequestInterface
{
    use ObjectTrait;

    #[UseParam]
    protected string $roomId;

    #[UseParam]
    protected string $userId;

    #[UseParam]
    protected array $position;

    public function getRoomId(): string
    {
        return $this->roomId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPosition(): array
    {
        return $this->position;
    }
}
