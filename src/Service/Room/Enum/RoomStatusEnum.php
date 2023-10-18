<?php
declare(strict_types=1);

namespace ForestServer\Service\Room\Enum;

enum RoomStatusEnum: string
{
    case Wait = 'wait';
    case Run = 'run';
    case Exit = 'exit';
}
