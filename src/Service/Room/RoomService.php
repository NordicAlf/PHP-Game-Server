<?php
declare(strict_types=1);

namespace ForestServer\Service\Room;

use ForestServer\Api\Request\RoomRequest;
use ForestServer\Api\Request\RoomUpdateRequest;
use ForestServer\DB\Entity\Room;
use ForestServer\DB\Repository\RoomRepository;
use ForestServer\DB\Repository\UserRepository;
use ForestServer\Exception\RoomAlreadyExistsException;
use ForestServer\Exception\RoomAlreadyStartedException;
use ForestServer\Exception\RoomNotFoundException;
use ForestServer\Exception\UserNotFoundException;
use ForestServer\Service\Room\Enum\RoomStatusEnum;

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

        $room = $this->roomRepository->getByPassword($request->getPassword());
        if ($room) {
            throw new RoomAlreadyExistsException($request->getPassword());
        }

        $room = (new Room())
            ->addUser($user)
            ->setPassword($request->getPassword())
            ->setRoomCreatorUserId($user)
        ;

        $user->setPosition(json_encode(['x' => 25, 'y' => 5, 'z' => 0]));
        $user->setRotation(json_encode(['x' => 0, 'y' => 0, 'z' => 0]));

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

        if ($room->getStatus() !== RoomStatusEnum::Wait->value) {
            throw new RoomAlreadyStartedException();
        }

        $room->addUser($user);
        $user->setPosition(json_encode(['x' => 25, 'y' => 5, 'z' => 0]));
        $user->setRotation(json_encode(['x' => 0, 'y' => 0, 'z' => 0]));

        $this->roomRepository->save($room);
        $this->userRepository->save($user);

        return $this;
    }

    public function run(RoomUpdateRequest $request): self
    {
        $room = $this->roomRepository->getById($request->getRoomId());

        $room->setStatus(RoomStatusEnum::Run);

        $this->roomRepository->save($room);

        return $this;
    }

    public function exitFromRoom(RoomUpdateRequest $request): self
    {
        $room = $this->roomRepository->getById($request->getRoomId());
        $user = $this->userRepository->getByFd((int)$request->getUserFd());

        if ($room->getRoomCreatorUserId() === $user->getId()) {
            $room->setStatus(RoomStatusEnum::Exit);
        }

        $room->removeUser($user);

        $this->roomRepository->save($room);

        return $this;
    }
}
