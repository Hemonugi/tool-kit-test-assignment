<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

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
}
