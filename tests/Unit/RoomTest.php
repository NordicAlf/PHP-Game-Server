<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit;

use ForestServer\DB\Entity\Room;
use ForestServer\DB\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class RoomTest extends TestCase
{
    public function testCreateRoom(): void
    {
        $room = new Room();
        $user1 = new User();
        $user2 = new User();

        $room->addUser($user1);
        $room->addUser($user2);

        foreach ($room->getUsers() as $userId) {
            $uuid = Uuid::fromString($userId->getId());

            $this->assertInstanceOf(UuidInterface::class, $uuid);
        }

        $this->assertEquals(2, count($room->getUsers()));
    }
}