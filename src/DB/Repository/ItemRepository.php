<?php
declare(strict_types=1);

namespace ForestServer\DB\Repository;

use ForestServer\DB\Entity\EntityInterface;
use ForestServer\DB\Entity\Item;
use ForestServer\DB\Storage\ItemStorage;
use ForestServer\DB\Storage\RoomStorage;
use ForestServer\Service\Transform\Transform;

class ItemRepository extends AbstractRepository implements RepositoryInterface
{
    public function getById(string $id): ?EntityInterface
    {
        $row = ItemStorage::getTable()->get($id);

        if ($row) {
            /** @var Object $objectPosition */
            $objectPosition = Transform::transformArrayToObject($row, Item::class);

            return $objectPosition;
        }

        return null;
    }

    public function getAll(): array
    {
        foreach (ItemStorage::getTable() as $row) {
            $objectPositions[] = Transform::transformArrayToObject($row, Item::class);
        }

        return $objectPositions ?? [];
    }

    /** @var Object $data */
    public function save(EntityInterface $data): void
    {
        ItemStorage::getTable()->set($data->getId(), [
            'id' => $data->getId(),
            'roomId' => $data->getRoomId(),
            'type' => $data->getType(),
            'position' => $data->getPosition()
        ]);
    }
}
