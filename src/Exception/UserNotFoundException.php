<?php
declare(strict_types=1);

namespace ForestServer\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class UserNotFoundException extends Exception implements NotFoundExceptionInterface
{
    public function __construct(int $userId)
    {
        parent::__construct("User with id '$userId' not found");
    }
}
