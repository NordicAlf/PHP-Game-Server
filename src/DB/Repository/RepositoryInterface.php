<?php
declare(strict_types=1);

namespace ForestServer\DB\Repository;

use ForestServer\DB\Entity\EntityInterface;

interface RepositoryInterface
{
    public function getById(string $id): ?EntityInterface;
    public function removeById(string $id): bool;
    public function getAll(): array;
    public function save(EntityInterface $data): void;
}
