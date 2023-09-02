<?php
declare(strict_types=1);

namespace ForestServer\DB\Entity;

use ForestServer\Attributes\UseParam;

class User extends AbstractEntity implements EntityInterface
{
    #[UseParam]
    protected int $fd;

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
