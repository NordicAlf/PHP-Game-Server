<?php
declare(strict_types=1);

namespace ForestServer\DB\Storage;

use Swoole\Table;

class ItemStorage implements StorageInterface
{
    private static Table $table;

    public static function getTable(): Table
    {
        if (!isset(self::$table)) {
            self::$table = new Table(10000);
            self::$table->column('id', Table::TYPE_STRING, 64);
            self::$table->column('roomId', Table::TYPE_STRING, 64);
            self::$table->column('type', Table::TYPE_STRING, 32);
            self::$table->column('position', Table::TYPE_STRING, 256);
            self::$table->create();

            return self::$table;
        }

        return self::$table;
    }
}
