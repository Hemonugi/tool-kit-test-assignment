<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\ChangeStatusDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\CreateDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\ViewDto;
use Psr\Clock\ClockInterface;

/**
 * Сущность заявки
 */
interface ApplicationInterface
{
    /**
     * Возвращает представление заявки
     * @return ViewDto
     */
    public function getViewDto(): ViewDto;

    /**
     * Обновляет статус заявки
     * @param ChangeStatusDto $dto
     * @return void
     */
    public function changeStatus(ChangeStatusDto $dto): void;

    /**
     * Добавляет новую заявку
     * @param CreateDto $dto
     * @param ClockInterface $clock
     * @return self
     */
    public static function create(CreateDto $dto, ClockInterface $clock): self;
}
