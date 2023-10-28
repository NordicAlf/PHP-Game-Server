<?php
declare(strict_types=1);

namespace ForestServer\Exception;

use Exception;

class RoomAlreadyExistsException extends Exception
{
    public function __construct(string $password)
    {
        parent::__construct("A room with a password $password already exists");
    }
}
