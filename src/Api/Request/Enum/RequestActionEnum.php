<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Enum;

enum RequestActionEnum: string
{
    case RoomCreate = 'room_create';
    case RoomJoin = 'room_join';
    case RoomRun = 'room_run';
    case RoomExit = 'room_exit';
    case PlayerPositionUpdate = 'player_position_update';
    case ObjectUpdate = 'object_update';
    case ObjectRemove = 'object_remove';
}
