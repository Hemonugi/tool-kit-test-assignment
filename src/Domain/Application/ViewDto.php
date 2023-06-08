<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use DateTimeInterface;

/**
 * Представление данных заявки
 */
readonly class ViewDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public DateTimeInterface $dateTime,
        public string $status
    ) {
    }
}
