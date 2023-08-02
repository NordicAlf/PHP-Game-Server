<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

interface RequestInterface
{
    public function create(string $json): RequestInterface;
}
