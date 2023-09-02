<?php
declare(strict_types=1);

namespace ForestServer\DB\Entity;

use ForestServer\Attributes\UseParam;
use ForestServer\Attributes\UseRelation;
use ForestServer\DB\Repository\UserRepository;

class Room extends AbstractEntity implements EntityInterface
{
    #[UseParam]
    protected string $password;

    #[UseRelation(repository: UserRepository::class)]
    private array $users = [];

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /** @return User[] */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }
}
