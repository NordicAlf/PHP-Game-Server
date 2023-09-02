<?php
declare(strict_types=1);

namespace ForestServer\Attributes;

use Attribute;

#[Attribute]
class UseRelation
{
    public string $repository;

    public function __construct(string $repository)
    {
        $this->repository = $repository;
    }
}
