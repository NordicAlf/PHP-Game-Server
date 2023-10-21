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

class RoomJoin implements GameStrategyInterface
{
    public function __construct(
        private RoomService $roomService,
        private RoomRepository $roomRepository
    ) {}

    public function process(Server $server, RequestInterface $request): void
    {
        $this->roomService->join($request);

        $room = $this->roomRepository->getByPassword($request->getPassword());

        foreach ($room->getUsers() as $user) {
            if ($server->isEstablished($user->getFd())) {
                $server->push($user->getFd(), json_encode([
                    'status' => ResponseStatusEnum::Success->value,
                    'action' => RequestActionEnum::RoomJoin->value,
                    'data' => [
                        'roomId' => $room->getId(),
                        'ownerPlayerId' => $user->getId(),
                        'roomCreatorUserId' => $room->getRoomCreatorUserId(),
                        'cakes' => $room->getItems(),
                        'users' => $room->getUsers()
                    ]
                ]));
            }
        }
    }

    public function getType(): RequestActionEnum
    {
        return RequestActionEnum::RoomJoin;
    }
}
