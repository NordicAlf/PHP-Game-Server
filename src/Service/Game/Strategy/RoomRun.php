<?php
declare(strict_types=1);

namespace ForestServer\Service\Game\Strategy;

use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\Service\Game\GameStrategyInterface;
use ForestServer\Service\Room\RoomService;
use ForestServer\Api\Response\Enum\ResponseStatusEnum;
use Swoole\WebSocket\Server;

class RoomRun implements GameStrategyInterface
{
    public function __construct(
        private RoomService $roomService,
        private RoomRepository $roomRepository
    ) {}

    public function process(Server $server, RequestInterface $request): void
    {
        $this->roomService->run($request);

        $room = $this->roomRepository->getById($request->getRoomId());

        foreach ($room->getUsers() as $user) {
            if ($server->isEstablished($user->getFd())) {
                $server->push($user->getFd(), json_encode([
                    'status' => ResponseStatusEnum::Success->value,
                    'action' => RequestActionEnum::RoomRun->value,
                    'data' => [
                        'roomStatus' => $room->getStatus(),
                    ]
                ]));
            }
        }
    }

    public function getType(): RequestActionEnum
    {
        return RequestActionEnum::RoomRun;
    }
}
