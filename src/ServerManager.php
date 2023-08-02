<?php
declare(strict_types=1);

namespace ForestServer;

use Closure;
use ForestServer\Api\Request\RequestInterface;
use ForestServer\DTO\Settings;
use ForestServer\Entity\User;
use Swoole\Http\Request;
use Swoole\Server\Port;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class ServerManager
{
    protected Server $server;
    protected Settings $settings;

    /* @var Port[] $users */
    protected array $ports = [];

    /* @var Room[] $users */
    protected array $rooms = [];

    /* @var User[] $users */
    protected array $users = [];

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly RequestInterface $request,
        ?Settings $settings = null,
    ) {
        $this->server = new Server($this->host, $this->port);

        if ($settings) {
            $this->server->set($settings->toArray());
        }

        $this->server->on('open', $this->onOpen($port));
        $this->server->on('message', $this->onMessage($port));
        $this->server->on('close', $this->onClose($port));
        $this->server->on('disconnect', $this->onClose($port));
    }

    public function createPort(int $port): self
    {
        $this->ports[$port] = $this->server->listen($this->host, $port, SWOOLE_SOCK_TCP);

        $this->ports[$port]->on('open', $this->onOpen($port));
        $this->ports[$port]->on('message', $this->onMessage($port));
        $this->ports[$port]->on('close', $this->onClose($port));
        $this->ports[$port]->on('disconnect', $this->onClose($port));

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
            $this->users[$request->fd] = (new User())->setFd($request->fd);

            echo "Client $request->fd :Connect to port: $port\n";
        };
    }

    public function onMessage(int $port): Closure
    {
        return function (Server $server, Frame $frame) use ($port) {
            $request = $this->request->create($frame->data);
            echo "Client $frame->fd :Request to port: $port\n";

            var_dump('request');
            var_dump($request);

            if ($server->isEstablished($frame->fd)) {
                foreach ($this->users as $user) {
                    $response = [
                        'user' => $frame->fd,
                        'message' => $frame->data
                    ];

                    $server->push($user->getFd(), json_encode($response));
                }
            }
        };
    }

    public function onClose(int $port): Closure
    {
        return function (Server $server, int $fd) use ($port) {
            unset($this->users[$fd]);

            echo "Client $fd :CLOSE to port: $port\n";
        };
    }

    public function createRoom(): self
    {
        $this->rooms[] = new Room();

        return $this;
    }
}
