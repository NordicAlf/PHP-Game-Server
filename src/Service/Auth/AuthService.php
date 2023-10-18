<?php
declare(strict_types=1);

namespace ForestServer\Service\Auth;

use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\DB\Entity\User;
use ForestServer\DB\Repository\UserRepository;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function authentication(RequestInterface $request): void
    {
        $user = (new User())->setFd((int)$request->getUserFd());

        if ($request->getAction() === RequestActionEnum::RoomCreate->value || $request->getAction() === RequestActionEnum::RoomJoin->value) {
            $this->userRepository->save($user);
        }
    }
}
