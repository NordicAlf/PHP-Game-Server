<?php
declare(strict_types=1);

namespace ForestServer\Service\Room;

use ForestServer\Api\Request\RoomRequest;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\DB\Repository\UserRepository;

class RoomService
{
    protected RoomRepository $roomRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->roomRepository = new RoomRepository();
        $this->userRepository = new UserRepository();
    }

    public function create(RoomRequest $request): self
    {
        $user = $this->userRepository->getByFd((int)$request->getUserFd());

        $room = (new Room())->addUser($user)->setPassword($request->getPassword());

        $this->roomRepository->save($room);

        return $this;
    }

    public function join(RoomRequest $request): self
    {
        $this->create($request);

        $user = $this->userRepository->getByFd((int)$request->getUserFd());
        $room = $this->roomRepository->getByPassword($request->getPassword());

        $room->addUser($user);

        $this->roomRepository->save($room);

        return $this;
    }
}
