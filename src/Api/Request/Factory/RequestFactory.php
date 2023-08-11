<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Factory;

use ForestServer\Api\Request\GameRequest;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Api\Request\RoomRequest;
use ForestServer\Transform;
use Swoole\WebSocket\Frame;
use Exception;

class RequestFactory
{
    public static function createRequest(Frame $frame): RequestInterface
    {
        $data = json_decode($frame->data, true);

        if (!isset($data['requestType'])) {
            throw new Exception('Bad Request');
        }

        return match($data['requestType']) {
            'room' => Transform::transformJsonToObject($data, RoomRequest::class),
            'game' => Transform::transformJsonToObject($data, GameRequest::class),
            default => throw new Exception('Bad Request')
        };
    }
}
