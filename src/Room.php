<?php
declare(strict_types=1);

namespace ForestServer;

use ForestServer\Entity\User;

class Room
{
    private array $users = [];

    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }
}
