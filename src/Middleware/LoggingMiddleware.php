<?php
declare(strict_types=1);

namespace ForestServer\Middleware;

use ForestServer\Api\Request\Interface\RequestInterface;
use Psr\Log\LoggerInterface;

class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function handle(RequestInterface $request, callable $next): ?callable
    {
        $this->logger->info($request->getType()->value, [
            'request' => [
//                'fd' => $frame->fd,
                'data' => $request->toArray()
            ]
        ]);

        return $next($request);
    }
}
