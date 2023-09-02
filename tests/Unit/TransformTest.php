<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit;

use ForestServer\Api\Request\RoomRequest;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Entity\User;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Service\Transform\Transform;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository();

        parent::setUp();
    }

    public function testTransform(): void
    {
        $json = [
            'roomAction' => 'Create',
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

        $this->userRepository->save($user);
        $this->userRepository->save($user2);

        $array = [
            'id' => '666',
            'password' => '55555',
            'users' => "[\"{$user->getId()}\",\"{$user2->getId()}\"]"
        ];

        /** @var Room $room $result */
        $room = Transform::transformArrayToObject($array, Room::class);

        $this->assertNotNull($room);
        $this->assertInstanceOf(User::class, $room->getUsers()[0]);
        $this->assertEquals($user->getId(), $room->getUsers()[0]->getId());
    }
}
