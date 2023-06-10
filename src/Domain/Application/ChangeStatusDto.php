<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

/**
 * Dto для изменения статуса заявки
 */
readonly final class ChangeStatusDto
{
    public function __construct(public ApplicationStatus $newStatus)
    {
    }
}
