<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Attributes\UseParam;

abstract class AbstractRequest
{
    #[UseParam]
    protected string $userFd;

    public function getUserFd(): string
    {
        return $this->userFd;
    }
}
