<?php
declare(strict_types=1);

namespace ForestServer\Middleware;

use ForestServer\Api\Request\Interface\RequestInterface;

final class MiddlewarePipeline
{
    /** @param list<MiddlewareInterface> $middleWares */
    public function __construct(
        private array $middleWares
    ) {}

    public function handle(RequestInterface $request): ?RequestInterface
    {
        $middleware = array_shift($this->middleWares);

        return $middleware?->handle($request, [$this, 'handle']);
    }
}