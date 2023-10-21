<?php
declare(strict_types=1);

namespace ForestServer\Service\Game\Strategy;

use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Api\Response\Enum\ResponseStatusEnum;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Game\GameManager;
use ForestServer\Service\Game\GameStrategyInterface;
use ForestServer\Service\Room\RoomService;
use Swoole\WebSocket\Server;

class RoomCreate implements GameStrategyInterface
{
    public function __construct(
        private RoomService $roomService,
        private GameManager $gameManager,
        private UserRepository $userRepository
    ) {}

    public function process(Server $server, RequestInterface $request): void
    {
        $room = $this->roomService->create($request);

        $room = $this->gameManager->createCakePositions($room);
        $user = $this->userRepository->getByFd((int)$request->getUserFd());

        if ($server->isEstablished((int)$request->getUserFd())) {
            $server->push((int)$request->getUserFd(), json_encode([
                'status' => ResponseStatusEnum::Success->value,
                'action' => RequestActionEnum::RoomCreate->value,
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

    public function getType(): RequestActionEnum
    {
        return RequestActionEnum::RoomCreate;
    }
}
