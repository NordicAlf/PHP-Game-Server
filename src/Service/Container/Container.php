<?php
declare(strict_types=1);

namespace ForestServer\Service\Container;

use ForestServer\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException('Class' . $id . 'has not binding');
        }

        $entry = $this->entries[$id];

        return $entry($this);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }
}
