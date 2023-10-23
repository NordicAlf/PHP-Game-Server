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
    protected string $status = 'wait';

    #[UseParam]
    protected string $roomCreatorUserId;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(RoomStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function getRoomCreatorUserId(): string
    {
        return $this->roomCreatorUserId;
    }

    public function setRoomCreatorUserId(User $user): self
    {
        $this->roomCreatorUserId = $user->getId();

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

    public function removeUser(User $user): self
    {
        $this->users = array_filter($this->users, function (User $userItem) use ($user) {
            return $userItem->getId() !== $user->getId();
        });

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

    public function removeObjects(): self
    {
        $this->items = [];

        return $this;
    }
}
