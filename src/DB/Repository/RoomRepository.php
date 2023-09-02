<?php
declare(strict_types=1);

namespace ForestServer\DB\Repository;

use ForestServer\DB\Entity\EntityInterface;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Storage\RoomStorage;
use ForestServer\Service\Transform\Transform;

class RoomRepository extends AbstractRepository implements RepositoryInterface
{
    public function getById(string $id): ?Room
    {
        $roomRow = RoomStorage::getTable()->get($id);

        if ($roomRow) {
            /** @var Room $room */
            $room = Transform::transformArrayToObject($roomRow, Room::class);

            return $room;
        }

        return null;
    }

    public function getByPassword(string $pass): ?Room
    {
        foreach (RoomStorage::getTable() as $roomRow) {
            if ($roomRow['password'] === $pass) {
                /** @var Room $room */
                $room = Transform::transformArrayToObject($roomRow, Room::class);

                return $room;
            }
        }

        return null;
    }

    public function getAll(): array
    {
        foreach (RoomStorage::getTable() as $roomRow) {
            $rooms[] = Transform::transformArrayToObject($roomRow, Room::class);
        }

        return $rooms ?? [];
    }

    /** @var Room $data */
    public function save(EntityInterface $data): void
    {
        $usersIds = [];

        foreach ($data->getUsers() as $user) {
            $usersIds[] = $user->getId();
        }

        RoomStorage::getTable()->set($data->getId(), [
            'id' => $data->getId(),
            'password' => $data->getPassword(),
            'users' => json_encode($usersIds)
        ]);
    }
}