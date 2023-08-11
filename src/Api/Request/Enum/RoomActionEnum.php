<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Enum;

enum RoomActionEnum: string
{
    case Create = 'Create';
    case Join = 'Join';
}
