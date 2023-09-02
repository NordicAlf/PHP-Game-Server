<?php
declare(strict_types=1);

namespace ForestServer\Middleware;

use ForestServer\Api\Request\Interface\RequestInterface;

interface MiddlewareInterface
{
    public function handle(RequestInterface $request): ?RequestInterface;
}
