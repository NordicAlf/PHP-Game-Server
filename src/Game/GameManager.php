<?php
declare(strict_types=1);

namespace ForestServer\Game;

use ForestServer\DB\Entity\Item;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Repository\ItemRepository;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\Service\Game\Enum\ItemTypeEnum;
use ForestServer\Service\Game\Generator\VectorGenerator;

class GameManager
{
    public function __construct(
        private VectorGenerator $generator,
        private ItemRepository  $itemRepository,
        private RoomRepository  $roomRepository
    ) {}

    public function createCakePositions(Room $room): Room
    {
        $minWorldPosition = -50;
        $maxWorldPosition = 70;
        $countPlates = 250;

        $minWorld = $minWorldPosition / 2;
        $maxWorld = $maxWorldPosition / 2;

        $positions = $this->generator->generate($countPlates, $minWorld, $maxWorld, 6, $maxWorld, $minWorld, $maxWorld);

//        var_dump('ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ');
//        var_dump($positions);

        foreach ($positions as $vector3) {
//            dump($vector3);
//            $res = json_encode($vector3);
//
//            dd(json_decode($res));

            $object = (new Item())
                ->setRoomId($room->getId())
                ->setType(ItemTypeEnum::Cake)
                ->setPosition(json_encode($vector3));

            $room->addObject($object);
            $this->itemRepository->save($object);
        }

        $this->roomRepository->save($room);

        return $room;

//        dump($this->itemRepository->getAll());
    }
}
