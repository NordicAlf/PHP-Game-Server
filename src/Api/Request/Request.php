<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Attributes\UseParam;

class Request extends AbstractRequest implements RequestInterface
{
    #[UseParam]
    private string $roomAction;

    #[UseParam]
    private string $roomPassword;

    public function getAction(): string
    {
        return $this->roomAction;
    }

    public function getPassword(): string
    {
        return $this->roomPassword;
    }
}

