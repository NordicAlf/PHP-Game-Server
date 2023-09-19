<?php
declare(strict_types=1);

namespace ForestServer\Service\Room;

use ForestServer\Api\Request\RoomRequest;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Exception\RoomNotFoundException;
use ForestServer\Exception\UserNotFoundException;
use ForestServer\Service\Game\Generator\VectorGenerator;

class RoomService
{
    protected RoomRepository $roomRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->roomRepository = new RoomRepository();
        $this->userRepository = new UserRepository();
    }

    public function create(RoomRequest $request): Room
    {
        $user = $this->userRepository->getByFd((int)$request->getUserFd());
        if (!$user) {
            throw new UserNotFoundException((int)$request->getUserFd());
        }

        $room = (new Room())->addUser($user)->setPassword($request->getPassword());

        $user->setPosition(json_encode([25, 5, 0]));

        $this->roomRepository->save($room);
        $this->userRepository->save($user);

        return $room;
    }

    public function join(RoomRequest $request): self
    {
        $user = $this->userRepository->getByFd((int)$request->getUserFd());
        $room = $this->roomRepository->getByPassword($request->getPassword());

        if (!$user) {
            throw new UserNotFoundException((int)$request->getUserFd());
        }
        if (!$room) {
            throw new RoomNotFoundException($request->getPassword());
        }

        $room->addUser($user);
        $user->setPosition(json_encode([25, 5, 0]));

        $this->roomRepository->save($room);
        $this->userRepository->save($user);

        return $this;
    }
}
