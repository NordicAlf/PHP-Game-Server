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

    public function removeById(string $id): bool
    {
        $room = $this->getById($id);

        if ($room) {
            RoomStorage::getTable()->delete($id);

            return true;
        }

        return false;
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
        foreach ($data->getUsers() as $user) {
            $userIds[] = $user->getId();
        }

        foreach ($data->getItems() as $item) {
            $itemIds[] = $item->getId();
        }

        RoomStorage::getTable()->set($data->getId(), [
            'id' => $data->getId(),
            'password' => $data->getPassword(),
            'roomCreatorUserId' => $data->getRoomCreatorUserId(),
            'status'=> $data->getStatus(),
            'users' => json_encode($userIds ?? []),
            'items' => json_encode($itemIds ?? [])
        ]);
    }
}
