<?php
declare(strict_types=1);

namespace ForestServer\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class RoomNotFoundException extends Exception implements NotFoundExceptionInterface
{
    public function __construct(string $password)
    {
        parent::__construct("Room by password '$password' not found");
    }
}
