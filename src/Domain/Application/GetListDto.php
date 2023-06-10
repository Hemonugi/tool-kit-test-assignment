<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use DateTimeInterface;

readonly final class GetListDto
{
    /**
     * @param ApplicationStatus[]|null $statuses список статусов заявок
     * @param DateTimeInterface|null $startDateTime заявки после этой даты будут попадать в выборку
     * @param DateTimeInterface|null $endDateTime заявки до этой даты будут попадать в выборку
     * @throws ValidationException
     */
    public function __construct(
        public ?array $statuses = null,
        public ?DateTimeInterface $startDateTime = null,
        public ?DateTimeInterface $endDateTime = null,
    ) {
        if (
            $this->startDateTime !== null && $this->endDateTime !== null
            && $this->startDateTime > $this->endDateTime
        ) {
            throw new ValidationException();
        }
    }
}
