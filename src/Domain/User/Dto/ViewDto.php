<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\User\Dto;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "UserView",
    properties:
    [
        new OA\Property(property: 'id', description: 'Идентификатор'),
        new OA\Property(property: 'nick', description: 'Ник'),
        new OA\Property(property: 'phone', description: 'Телефон'),
        new OA\Property(property: 'address', description: 'Адрес'),
    ]
)]
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
