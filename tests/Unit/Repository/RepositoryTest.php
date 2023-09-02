<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit\Repository;

use ForestServer\DB\Entity\User;
use ForestServer\DB\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    protected UserRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new UserRepository();
    }

    public function testRepository()
    {
        $user = (new User())
            ->setFd(10);

        $this->repository->save($user);

        $users = $this->repository->getAll();

        $this->assertNotNull($users);
    }
}
