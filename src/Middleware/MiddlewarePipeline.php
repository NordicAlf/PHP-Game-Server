<?php
declare(strict_types=1);

namespace ForestServer\Middleware;

use ForestServer\Api\Request\Interface\RequestInterface;

final class MiddlewarePipeline
{
    /** @param list<MiddlewareInterface> $middleWares */
    public function __construct(
        private readonly array $middleWares
    ) {}

    public function handle(RequestInterface $request): void
    {
        foreach ($this->middleWares as $middleware) {
            $request = $middleware->handle($request);
        }
    }
}
