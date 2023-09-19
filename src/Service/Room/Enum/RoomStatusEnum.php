<?php
declare(strict_types=1);

namespace ForestServer\Service\Room\Enum;

enum RoomStatusEnum: int
{
    case Wait = 0;
    case Run = 1;
}
