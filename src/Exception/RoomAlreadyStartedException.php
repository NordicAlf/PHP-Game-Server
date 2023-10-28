<?php
declare(strict_types=1);

namespace ForestServer\Exception;

use Exception;

class RoomAlreadyStartedException extends Exception
{
    public function __construct()
    {
        parent::__construct("The room has already been started by the player-creator");
    }
}
