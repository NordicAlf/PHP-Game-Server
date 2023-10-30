<?php
declare(strict_types=1);

namespace ForestServer;

use Closure;
use DI\Container;
use Exception;
use ForestServer\Api\Request\Factory\RequestFactory;
use ForestServer\DTO\Settings;
use ForestServer\Middleware\AuthMiddleware;
use ForestServer\Middleware\LoggingMiddleware;
use ForestServer\Middleware\MiddlewarePipeline;
use ForestServer\Service\Auth\AuthService;
use ForestServer\Service\Container\ServiceContainer;
use ForestServer\Service\Game\GameProcessor;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ForestServer\Api\Response\Enum\ResponseStatusEnum;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Server\Port;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class ServerManager
{
    private Server $server;
    private MiddlewarePipeline $middleware;
    private GameProcessor $gameProcessor;

    /* @var Port[] $users */
    protected array $ports = [];

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        Settings $settings,
    ) {
        ServiceContainer::set(new Container());

        $this->middleware = new MiddlewarePipeline([
            new LoggingMiddleware(new Logger('main', [new StreamHandler('php://stdout')])),
            new AuthMiddleware(new AuthService())
        ]);

        if ($settings->isSsl()) {
            $this->server = new Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        } else {
            $this->server = new Server($this->host, $this->port);
        }

        $this->server->set($settings->toArray());

        $this->server->on('open', $this->onOpen($port));
        $this->server->on('message', $this->onMessage($port));
        $this->server->on('close', $this->onClose($port));
        $this->server->on('disconnect', $this->onClose($port));

        $this->gameProcessor = new GameProcessor($this->server);
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
        return function (Server $server, SwooleRequest $request) use ($port) {
            echo "Client $request->fd :Connect to port: $port\n";
        };
    }

    public function onMessage(int $port): Closure
    {
        return function (Server $server, Frame $frame) use ($port) {
            try {
                $request = RequestFactory::createRequest($frame);

                $this->middleware->handle($request);

                go(function () use ($request) {
                    $this->gameProcessor->process($request);
                });
            } catch (Exception $exception) {
                var_dump($exception->getMessage());

                $server->push($frame->fd, json_encode([
                    'status' => ResponseStatusEnum::Error->value,
                    'action' => $request->getAction(),
                    'data' => $exception->getMessage()
                ]));
            }
        };
    }

    public function onClose(int $port): Closure
    {
        return function (Server $server, int $fd) use ($port) {
            // TODO Сделать удаление пользователя из базы при дисконнекте

            echo "Client $fd :CLOSE to port: $port\n";
        };
    }
}
