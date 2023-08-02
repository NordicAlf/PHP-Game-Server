<?php
declare(strict_types=1);

namespace ForestServer\Entity;

class User extends AbstractEntity
{
    private int $fd;

    public function getFd(): int
    {
        return $this->fd;
    }

    public function setFd(int $fd): self
    {
        $this->fd = $fd;

        return $this;
    }
}