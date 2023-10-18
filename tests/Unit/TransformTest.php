<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit;

use ForestServer\Api\Request\RoomRequest;
use ForestServer\DB\Entity\Item;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Entity\User;
use ForestServer\DB\Repository\ItemRepository;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Service\Room\Enum\RoomStatusEnum;
use ForestServer\Service\Transform\Transform;
use ForestServer\Service\Utils\Enum\ItemTypeEnum;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase
{
    private UserRepository $userRepository;
    private ItemRepository $itemRepository;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository();
        $this->itemRepository = new ItemRepository();

        parent::setUp();
    }

    public function testTransform(): void
    {
        $json = [
            'action' => 'Create',
            'roomPassword' => '11111',
            'userFd' => '666'
        ];

        $result = Transform::transformArrayToObject($json, RoomRequest::class);

        $this->assertNotNull($result);
        $this->assertIsArray($result->toArray());
    }

    public function testTransformToEntity(): void
    {
        $user = (new User())->setFd(666);
        $user2 = (new User())->setFd(777);

        $object1 = (new Item())->setType(ItemTypeEnum::Cake)->setRoomId('666')->setPosition('');
        $object2 = (new Item())->setType(ItemTypeEnum::Cake)->setRoomId('666')->setPosition('');

        $this->userRepository->save($user);
        $this->userRepository->save($user2);
        $this->itemRepository->save($object1);
        $this->itemRepository->save($object2);

        $array = [
            'id' => '666',
            'password' => '55555',
            'roomCreatorUserId' => '666',
            'status' => RoomStatusEnum::Wait->value,
            'users' => "[\"{$user->getId()}\",\"{$user2->getId()}\"]",
            'items' => "[\"{$object1->getId()}\",\"{$object2->getId()}\"]"
        ];

        /** @var Room $room $result */
        $room = Transform::transformArrayToObject($array, Room::class);

        $this->assertNotNull($room);
        $this->assertInstanceOf(User::class, $room->getUsers()[0]);
        $this->assertEquals($user->getId(), $room->getUsers()[0]->getId());
    }
}
