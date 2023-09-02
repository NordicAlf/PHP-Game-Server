<?php
declare(strict_types=1);

namespace ForestServer\DB\Storage;

use Swoole\Table;

interface StorageInterface
{
    public static function getTable(): Table;
}
