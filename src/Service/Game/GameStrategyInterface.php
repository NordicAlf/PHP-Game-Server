<?php
declare(strict_types=1);

namespace ForestServer\Service\Game;

use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use Swoole\WebSocket\Server;

interface GameStrategyInterface
{
    public function process(Server $server, RequestInterface $request): void;
    public function getType(): RequestActionEnum;
}
