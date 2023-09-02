<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Factory;

use Exception;
use ForestServer\Api\Request\GameRequest;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Api\Request\RoomRequest;
use ForestServer\Service\Transform\Transform;
use Swoole\WebSocket\Frame;

class RequestFactory
{
    public static function createRequest(Frame $frame): RequestInterface
    {
        $data = json_decode($frame->data, true);
        $data['userFd'] = (string) $frame->fd;

        if (!isset($data['requestType'])) {
            throw new Exception('Bad Request');
        }

        // TO-DO use enum
        return match($data['requestType']) {
            'room' => Transform::transformArrayToObject($data, RoomRequest::class),
            'game' => Transform::transformArrayToObject($data, GameRequest::class),
            default => throw new Exception('Bad Request')
        };
    }
}
