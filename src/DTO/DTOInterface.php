<?php
declare(strict_types=1);

namespace ForestServer\DTO;

interface DTOInterface
{
    public function toArray(): array;
}