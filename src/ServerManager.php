<?php
declare(strict_types=1);

namespace ForestServer;

use ForestServer\DTO\Settings;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Closure;

class ServerManager
{
    protected Server $server;
    protected array $ports = [];
    protected Settings $settings;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        ?Settings $settings = null
    ) {
        $this->server = new Server($this->host, $this->port);

        if ($settings) {
            $this->server->set($settings->toArray());
        }

        $this->server->on('open', $this->onOpen($port));
        $this->server->on('message', $this->onMessage($port));
    }

    public function createPort(int $port): self
    {
        $this->ports[$port] = $this->server->listen($this->host, $port, SWOOLE_SOCK_TCP);

        $this->ports[$port]->on('open', $this->onOpen($port));
        $this->ports[$port]->on('message', $this->onMessage($port));

        return $this;
    }

    public function startServer(): self
    {
        $this->server->start();

        return $this;
    }

    public function shutdownServer(): self
    {
        $this->server->shutdown();

        echo "Server is closed";

        return $this;
    }

    public function onOpen(int $port): Closure
    {
        return function (Server $server, Request $request) use ($port) {
            echo "Client:Connect port: $port\n";
        };
    }

    public function onMessage(int $port): Closure
    {
        return function (Server $server, Frame $frame) use ($port) {
            echo "Client:Request. port: $port\n";
        };
    }
}
