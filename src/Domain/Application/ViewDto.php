<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use DateTimeInterface;
use JsonSerializable;

/**
 * Представление данных заявки
 */
readonly final class ViewDto implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public DateTimeInterface $dateTime,
        public string $status
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'createDate' => $this->dateTime->format('Y-m-d H:i:s'),
            'status' => $this->status,
        ];
    }
}
