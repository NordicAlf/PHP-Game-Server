<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Attributes\UseParam;

abstract class AbstractRequest
{
    #[UseParam]
    protected string $userFd;

    #[UseParam]
    protected string $action;

    public function getUserFd(): string
    {
        return $this->userFd;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
