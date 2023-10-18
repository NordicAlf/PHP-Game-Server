<?php
declare(strict_types=1);

namespace ForestServer\Api\Request\Factory;

use Exception;
use ForestServer\Api\Request\Enum\RequestActionEnum;
use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Api\Request\ObjectRequest;
use ForestServer\Api\Request\PlayerRequest;
use ForestServer\Api\Request\RoomRequest;
use ForestServer\Api\Request\RoomUpdateRequest;
use ForestServer\Service\Transform\Transform;
use Swoole\WebSocket\Frame;

class RequestFactory
{
    public static function createRequest(Frame $frame): RequestInterface
    {
        $data = json_decode($frame->data, true);
        $data['userFd'] = (string) $frame->fd;

        if (!isset($data['action'])) {
            throw new Exception('Bad Request');
        }

        return match ($data['action']) {
            RequestActionEnum::RoomCreate->value,
            RequestActionEnum::RoomJoin->value => Transform::transformArrayToObject($data, RoomRequest::class),
            RequestActionEnum::PlayerPositionUpdate->value => Transform::transformArrayToObject($data, PlayerRequest::class),
            RequestActionEnum::ObjectRemove->value => Transform::transformArrayToObject($data, ObjectRequest::class),
            RequestActionEnum::RoomRun->value,
            RequestActionEnum::RoomExit->value => Transform::transformArrayToObject($data, RoomUpdateRequest::class),
            default => throw new Exception('Bad Request. Request action not found on the server'),
        };
    }
}
