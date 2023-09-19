<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Enum;

enum ResponseTypeEnum: string
{
    case Positions = 'positions';
    case DataChannel = 'data_channel';
}
