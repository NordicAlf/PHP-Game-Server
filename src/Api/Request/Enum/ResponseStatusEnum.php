<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Enum;

enum ResponseStatusEnum: string
{
    case Success = 'success';
    case Error = 'error';
}
