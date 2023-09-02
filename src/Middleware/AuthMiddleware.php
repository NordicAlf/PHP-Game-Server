<?php
declare(strict_types=1);

namespace ForestServer\Middleware;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Service\Auth\AuthService;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthService $auth
    ) {}

    public function handle(RequestInterface $request): ?RequestInterface
    {
        $this->auth->authentication($request);

        return $request;
    }
}
