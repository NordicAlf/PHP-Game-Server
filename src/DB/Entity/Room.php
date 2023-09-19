<?php
declare(strict_types=1);

namespace ForestServer\DB\Entity;

use ForestServer\Attributes\UseParam;
use ForestServer\Attributes\UseRelation;
use ForestServer\DB\Repository\ItemRepository;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Service\Room\Enum\RoomStatusEnum;

class Room extends AbstractEntity implements EntityInterface
{
    #[UseParam]
    protected string $password;

    #[UseParam]
    protected int $status = RoomStatusEnum::Wait->value;

    #[UseRelation(repository: UserRepository::class)]
    protected array $users = [];

    #[UseRelation(repository: ItemRepository::class)]
    protected array $items = [];

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

    /** @return Item[] */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addObject(Item $item): self
    {
        $this->items[] = $item;

        return $this;
    }
}
