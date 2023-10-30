<?php
declare(strict_types=1);

namespace ForestServer\Service\Game\Strategy;

use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\Game\GameManager;
use ForestServer\Service\Game\GameStrategyInterface;
use ForestServer\Api\Response\Enum\ResponseStatusEnum;
use Swoole\WebSocket\Server;

class PlayerPositionUpdate implements GameStrategyInterface
{
    public function __construct(
        private GameManager $gameManager,
        private RoomRepository $roomRepository
    ) {}

    public function process(Server $server, RequestInterface $request): void
    {
        $this->gameManager->updateUserData($request);

        $room = $this->roomRepository->getById($request->getRoomId());

        $userData = [];

        foreach ($room->getUsers() as $user) {
            $userData[$user->getId()]['position'] = json_decode($user->getPosition());
            $userData[$user->getId()]['rotation'] = json_decode($user->getRotation());
        }

        foreach ($room->getUsers() as $user) {
            if ($server->isEstablished($user->getFd())) {
                $server->push($user->getFd(), json_encode([
                    'status' => ResponseStatusEnum::Success->value,
                    'action' => RequestActionEnum::PlayerPositionUpdate->value,
                    'data' => [
                        'users' => $userData
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
