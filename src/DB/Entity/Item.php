<?php
declare(strict_types=1);

namespace ForestServer\DB\Entity;

use ForestServer\Attributes\UseParam;
use ForestServer\Service\Game\Enum\ItemTypeEnum;
use JsonSerializable;

class Item extends AbstractEntity implements EntityInterface, JsonSerializable
{
    #[UseParam]
    protected string $roomId;

    #[UseParam]
    protected string $type;

    #[UseParam]
    protected string $position;

    public function getRoomId(): string
    {
        return $this->roomId;
    }

    public function setRoomId(string $id): self
    {
        $this->roomId = $id;

        return $this;

    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(ItemTypeEnum $type): self
    {
        $this->type = $type->value;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $vector3): self
    {
        $this->position = $vector3;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'roomId' => $this->roomId,
            'type' => $this->type,
            'position' => json_decode($this->position)
        ];
    }
}
