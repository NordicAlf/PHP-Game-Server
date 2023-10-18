<?php
declare(strict_types=1);

namespace ForestServer\Service\Game\Strategy;

use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\Game\GameManager;
use ForestServer\Service\Game\GameStrategyInterface;
use ResponseStatusEnum;
use Swoole\WebSocket\Server;

class PlayerPositionUpdate implements GameStrategyInterface
{
    public function __construct(
        private GameManager $gameManager,
        private RoomRepository $roomRepository
    ) {}

    public function process(Server $server, RequestInterface $request): void
    {
        $this->gameManager->updateUserPosition($request);

        $room = $this->roomRepository->getById($request->getRoomId());

        $userPositions = [];

        foreach ($room->getUsers() as $user) {
            $userPositions[$user->getId()] = json_decode($user->getPosition());
        }

        foreach ($room->getUsers() as $user) {
            if ($server->isEstablished($user->getFd())) {
                $server->push($user->getFd(), json_encode([
                    'status' => ResponseStatusEnum::Success->value,
                    'action' => RequestActionEnum::PlayerPositionUpdate->value,
                    'data' => [
                        'users' => $userPositions
                    ]
                ]));
            }
        }
    }

    public function getType(): RequestActionEnum
    {
        return RequestActionEnum::PlayerPositionUpdate;
    }
}
