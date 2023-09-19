<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit\Service;

use ForestServer\Service\Game\Generator\VectorGenerator;
use PHPUnit\Framework\TestCase;

class VectorGeneratorTest extends TestCase
{
    private VectorGenerator $generator;

    public function setUp(): void
    {
        $this->generator = new VectorGenerator();

        parent::setUp();
    }

    /** @dataProvider dataProvider */
    public function testCreateVectorPositions(int $countPositions, int $minX, int $maxX, int $minY, int $maxY, int $minZ, int $maxZ)
    {
        $positions = $this->generator->generate($countPositions, $minX, $maxX, $minY, $maxY, $minZ, $maxZ);

        $this->assertEquals($countPositions, count($positions));

        foreach ($positions as $position) {
            $this->assertTrue($position[0] >= $minX && $position[0] <= $maxX);
            $this->assertTrue($position[1] >= $minY && $position[1] <= $maxY);
            $this->assertTrue($position[2] >= $minZ && $position[2] <= $maxZ);
        }
    }

    public static function dataProvider(): array
    {
        return [
            [10, -10, 10, -20, 20, -30, 30],
        ];
    }
}
