<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit;

use ForestServer\Api\Request\RoomRequest;
use ForestServer\Transform;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase
{
    public function testTransform(): void
    {
        $json = [
            'roomAction' => 'Create',
            'roomPassword' => '11111'
        ];

        $result = Transform::transformJsonToObject($json, RoomRequest::class);

        $this->assertNotNull($result);
        $this->assertIsArray($result->toArray());
    }
}
