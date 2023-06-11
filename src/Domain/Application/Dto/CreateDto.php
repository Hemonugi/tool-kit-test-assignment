<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application\Dto;

use Hemonugi\ToolKitTestAssignment\Domain\User\UserInterface;

/**
 * Дто для создания заявки
 */
readonly final class CreateDto
{
    public function __construct(
        public string $title,
        public string $text,
        public UserInterface $creator,
    ) {
    }
}
