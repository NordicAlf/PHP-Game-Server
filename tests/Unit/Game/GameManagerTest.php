<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit;

use ForestServer\DB\Entity\Item;
use ForestServer\DB\Repository\ItemRepository;
use ForestServer\Service\Utils\Enum\ItemTypeEnum;
use PHPUnit\Framework\TestCase;

class GameManagerTest extends TestCase
{
    private ItemRepository $itemRepository;

    public function setUp(): void
    {
        $this->itemRepository = new ItemRepository();

        parent::setUp();
    }

    public function testRemoveCake(): void
    {
        $item = new Item();
        $item->setType(ItemTypeEnum::Cake);
        $item->setRoomId('666');
        $item->setPosition(json_encode([-5, 5, 10]));

        $this->itemRepository->save($item);

        $savedItem = $this->itemRepository->getById($item->getId());

        $this->itemRepository->removeById($savedItem->getId());

        $removedItem = $this->itemRepository->getById($item->getId());

        $this->assertNotNull($savedItem);
        $this->assertNull($removedItem);
    }
}
