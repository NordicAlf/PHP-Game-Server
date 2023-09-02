<?php
declare(strict_types=1);

namespace ForestServer\DB\Entity;

use ForestServer\Attributes\UseParam;
use Ramsey\Uuid\Uuid;

abstract class AbstractEntity
{
    #[UseParam]
    protected string $id;

    public function __construct()
    {
        $this->id = (string) Uuid::uuid4();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
