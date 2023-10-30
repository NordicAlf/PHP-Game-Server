<?php
declare(strict_types=1);

namespace ForestServer\Game;

use ForestServer\Api\Request\ObjectRequest;
use ForestServer\Api\Request\PlayerRequest;
use ForestServer\DB\Entity\Item;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Repository\ItemRepository;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Service\Utils\Enum\ItemTypeEnum;
use ForestServer\Service\Utils\Generator\VectorGenerator;

class GameManager
{
    public function __construct(
        private VectorGenerator $generator,
        private ItemRepository  $itemRepository,
        private RoomRepository  $roomRepository,
        private UserRepository $userRepository
    ) {}

    public function createCakePositions(Room $room): Room
    {
        $minWorldPosition = -50;
        $maxWorldPosition = 70;
        $countPlates = 250;

        $minWorld = $minWorldPosition / 2;
        $maxWorld = $maxWorldPosition / 2;

        $positions = $this->generator->generate($countPlates, $minWorld, $maxWorld, 6, $maxWorld, $minWorld, $maxWorld);

        foreach ($positions as $vector3) {
            $object = (new Item())
                ->setRoomId($room->getId())
                ->setType(ItemTypeEnum::Cake)
                ->setPosition(json_encode($vector3));

            $room->addObject($object);
            $this->itemRepository->save($object);
        }

        $this->roomRepository->save($room);

        return $room;
    }

    public function removeCake(ObjectRequest $request): void
    {
        $room = $this->roomRepository->getById($request->getRoomId());

        $removeItemFromRoomItems = array_filter($room->getItems(), function (Item $item) use ($request) {
            return $item->getId() !== $request->getObjectId();
        });

        $room->removeObjects();

        foreach ($removeItemFromRoomItems as $item) {
            $room->addObject($item);
        }

        $this->roomRepository->save($room);
        $this->itemRepository->removeById($request->getObjectId());
    }

    public function updateUserData(PlayerRequest $request): void
    {
       $user = $this->userRepository->getById($request->getUserId());

       $user->setPosition(json_encode($request->getPosition()));
       $user->setRotation(json_encode($request->getRotation()));

       $this->userRepository->save($user);
    }
}
