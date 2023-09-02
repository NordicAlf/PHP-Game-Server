<?php
declare(strict_types=1);

namespace ForestServer\DB\Storage;

use Swoole\Table;

class RoomStorage implements StorageInterface
{
    private static Table $table;

    public static function getTable(): Table
    {
        if (!isset(self::$table)) {
            self::$table = new Table(1024);
            self::$table->column('id', Table::TYPE_STRING, 64);
            self::$table->column('password', Table::TYPE_STRING, 64);
            self::$table->column('users', Table::TYPE_STRING, 256);
            self::$table->create();

            return self::$table;
        }

        return self::$table;
    }
}
