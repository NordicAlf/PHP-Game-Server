<?php
declare(strict_types=1);

namespace ForestServer\DB\Storage;

use Swoole\Table;

class UserStorage implements StorageInterface
{
    private static Table $table;

    public static function getTable(): Table
    {
        if (!isset(self::$table)) {
            self::$table = new Table(1024);
            self::$table->column('id', Table::TYPE_STRING, 64);
            self::$table->column('fd', Table::TYPE_INT, 32);
            self::$table->create();

            return self::$table;
        }

        return self::$table;
    }
}
