<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit;

use ForestServer\Api\Request\Request;
use ForestServer\Transform;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase
{
    private Transform $transform;

    protected function setUp(): void
    {
        $this->transform = new Transform();
    }

    public function testTransform(): void
    {
        $json = json_encode([
            'roomAction' => 'Create',
            'roomPassword' => '11111'
        ]);

        $result = $this->transform->transformJsonToObject($json, Request::class);

        dd($result);
    }
}