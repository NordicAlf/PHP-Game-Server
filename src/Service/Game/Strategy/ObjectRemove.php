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

class ObjectRemove implements GameStrategyInterface
{
    public function __construct(
        private GameManager $gameManager,
        private RoomRepository $roomRepository
    ) {}

    public function process(Server $server, RequestInterface $request): void
    {
        $this->gameManager->removeCake($request);
        $room = $this->roomRepository->getById($request->getRoomId());

        $server->push((int)$request->getUserFd(), json_encode([
            'status' => ResponseStatusEnum::Success->value,
            'action' => RequestActionEnum::ObjectRemove->value,
            'data' => [
                'cakes' => $room->getItems()
            ]
        ]));
    }

    public function getType(): RequestActionEnum
    {
        return RequestActionEnum::ObjectRemove;
    }
}
