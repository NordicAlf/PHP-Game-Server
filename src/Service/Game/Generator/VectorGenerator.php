<?php
declare(strict_types=1);

namespace ForestServer\Service\Game\Generator;

class VectorGenerator
{
    public function generate(int $countPositions, int $minX, int $maxX, int $minY, int $maxY, int $minZ, int $maxZ): array
    {
        $positions = [];

        for ($i = 0; $i < $countPositions; $i++) {
            $positions[] = [
                rand($minX, $maxX),
                rand($minY, $maxY),
                rand($minZ, $maxZ),
            ];
        }

        return $positions;
    }
}
