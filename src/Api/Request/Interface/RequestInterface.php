<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Interface;

interface RequestInterface
{
    public function getAction(): string;
    public function getUserFd(): string;
    public function toArray(): array;
}
