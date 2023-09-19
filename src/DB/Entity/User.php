<?php
declare(strict_types=1);

namespace ForestServer\DB\Entity;

use ForestServer\Attributes\UseParam;
use JsonSerializable;

class User extends AbstractEntity implements EntityInterface, JsonSerializable
{
    #[UseParam]
    protected int $fd;

    #[UseParam]
    protected string $position = '';

    public function getFd(): int
    {
        return $this->fd;
    }

    public function setFd(int $fd): self
    {
        $this->fd = $fd;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'position' => json_decode($this->position)
        ];
    }
}
