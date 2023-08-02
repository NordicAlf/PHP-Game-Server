<?php
declare(strict_types=1);

namespace ForestServer\Api\Request;

use ForestServer\Transform;

class AbstractRequest
{
    protected Transform $transform;

    public function __construct()
    {
        $this->transform = new Transform();
    }

    public function create(string $json): RequestInterface
    {
        return $this->transform->transformJsonToObject($json, self::class);
    }
}
