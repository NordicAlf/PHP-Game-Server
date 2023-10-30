<?php
declare(strict_types=1);

namespace ForestServer\DB\Repository;

use ForestServer\DB\Entity\EntityInterface;
use ForestServer\DB\Entity\User;
use ForestServer\DB\Storage\UserStorage;
use ForestServer\Service\Transform\Transform;

class UserRepository extends AbstractRepository implements RepositoryInterface
{
    public function getById(string $id): ?User
    {
        $userRow = UserStorage::getTable()->get($id);

        if ($userRow) {
            /** @var User $user */
            $user = Transform::transformArrayToObject($userRow, User::class);

            return $user;
        }

        return null;
    }

    public function removeById(string $id): bool
    {
        $user = $this->getById($id);

        if ($user) {
            UserStorage::getTable()->delete($id);

            return true;
        }

        return false;
    }

    public function getByFd(int $fd): ?User
    {
        foreach (UserStorage::getTable() as $userRow) {
            if ($userRow['fd'] == $fd) {
                /** @var User $user */
                $user = Transform::transformArrayToObject($userRow, User::class);

                return $user;
            }
        }

        return null;
    }

    /** @return User[] */
    public function getAll(): array
    {
        foreach (UserStorage::getTable() as $userRow) {
            $users[] = Transform::transformArrayToObject($userRow, User::class);
        }

        return $users ?? [];
    }

    /** @var User $data */
    public function save(EntityInterface $data): void
    {
        UserStorage::getTable()->set($data->getId(), [
            'id' => $data->getId(),
            'fd' => $data->getFd(),
            'position' => $data->getPosition(),
            'rotation' => $data->getRotation()
        ]);
    }
}
