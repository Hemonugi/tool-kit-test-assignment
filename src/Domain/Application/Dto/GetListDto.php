<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application\Dto;

use DateTimeInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Exception\ValidationException;

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
        public ?int $creatorId = null,
    ) {
        if (
            $this->startDateTime !== null && $this->endDateTime !== null
            && $this->startDateTime > $this->endDateTime
        ) {
            throw new ValidationException();
        }
    }
}
