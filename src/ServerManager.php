<?php
declare(strict_types=1);

namespace ForestServer;

use Closure;
use Exception;
use ForestServer\Api\Request\Enum\ResponseStatusEnum;
use ForestServer\Api\Request\Enum\ResponseTypeEnum;
use ForestServer\Api\Request\Enum\RoomActionEnum;
use ForestServer\Api\Request\Factory\RequestFactory;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Repository\ItemRepository;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\DTO\Settings;
use ForestServer\Game\GameManager;
use ForestServer\Middleware\AuthMiddleware;
use ForestServer\Middleware\LoggingMiddleware;
use ForestServer\Middleware\MiddlewarePipeline;
use ForestServer\Service\Auth\AuthService;
use ForestServer\Service\Container\Container;
use ForestServer\Service\Game\Generator\VectorGenerator;
use ForestServer\Service\Room\RoomService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Server\Port;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class ServerManager
{
    protected Server $server;
    protected Settings $settings;
    protected MiddlewarePipeline $middleware;
    protected RoomService $roomManager;
    protected static Container $container;
    protected RoomRepository $roomRepository;
    protected GameManager $gameManager;

    /* @var Port[] $users */
    protected array $ports = [];

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        ?Settings $settings = null,
    ) {
        $this->roomRepository = new RoomRepository();
        $this->roomManager = new RoomService();
        $this->gameManager = new GameManager(new VectorGenerator(), new ItemRepository(), $this->roomRepository);
//        static::$container = new Container();

//        static::$container->set(RoomService::class, function (Container $c) {
//            return new RoomService();
//        });

        $this->middleware = new MiddlewarePipeline([
            new LoggingMiddleware(new Logger('main', [new StreamHandler('php://stdout')])),
            new AuthMiddleware(new AuthService())
        ]);

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

                if ($request->getAction() === RoomActionEnum::Create->value) {
                    $room = $this->roomManager->create($request);

                    $room = $this->gameManager->createCakePositions($room);

                    $server->push($frame->fd, json_encode([
                        'status' => ResponseStatusEnum::Success->value,
                        'type' => ResponseTypeEnum::Positions->value,
                        'data' => [
                            'platePositions' => []
                        ]
                    ]));
                }

                if ($request->getAction() === RoomActionEnum::Join->value) {
                    //создать позицую игрока для нового юзера

                    $this->roomManager->join($request);
                }

//                var_dump($this->roomRepository->getAll());
            } catch (Exception $exception) {
                var_dump($exception->getMessage());

                $server->push($frame->fd, json_encode([
                    'status' => ResponseStatusEnum::Error->value,
                    'data' => $exception->getMessage()
                ]));
            }

            if ($server->isEstablished($frame->fd)) {
                /** @var Room $room */
                foreach ($this->roomRepository->getAll() as $room) {
                    dump(json_encode($room->getUsers()[0]));

                    $server->push($frame->fd, json_encode([
                        'status' => ResponseStatusEnum::Success->value,
                        'type' => ResponseTypeEnum::DataChannel->value,
                        'data' => [
                            'cakes' => $room->getItems(),
                            'users' => $room->getUsers()
                        ]
                    ]));
                }
            }
        };
    }

    public function onClose(int $port): Closure
    {
        return function (Server $server, int $fd) use ($port) {
//            unset($this->users[$fd]);

            echo "Client $fd :CLOSE to port: $port\n";
        };
    }
}
