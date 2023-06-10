<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\User;

readonly final class ViewDto implements \JsonSerializable
{
    public function __construct(
        public int $id,
        public string $nick,
        public string $phone,
        public string $address,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nick' => $this->nick,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}
