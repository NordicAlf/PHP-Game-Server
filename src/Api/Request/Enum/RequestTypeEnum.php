<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Enum;

enum RequestTypeEnum: string
{
    case Room = 'Room';
    case Game = 'Game';
}
