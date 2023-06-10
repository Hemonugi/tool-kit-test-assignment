<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use DateTimeInterface;
use JsonSerializable;
use OpenApi\Attributes as OA;

/**
 * Представление данных заявки
 */
#[OA\Schema(
    title: "ApplicationView",
    properties:
        [
            new OA\Property(property: 'id', description: 'Идентификатор'),
            new OA\Property(property: 'title', description: 'Заголовок'),
            new OA\Property(property: 'text', description: 'Описание'),
            new OA\Property(property: 'createDate', description: 'Дата создания заявки'),
            new OA\Property(property: 'status', description: 'Статус'),
        ]
)]
readonly final class ViewDto implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public DateTimeInterface $createDate,
        public string $status
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'createDate' => $this->createDate->format('Y-m-d H:i:s'),
            'status' => $this->status,
        ];
    }
}
