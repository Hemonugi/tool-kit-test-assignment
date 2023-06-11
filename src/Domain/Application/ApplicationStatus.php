<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use Hemonugi\ToolKitTestAssignment\Domain\Application\Exception\ValidationException;

enum ApplicationStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
    case Archived = 'archived';

    /**
     * @throws ValidationException
     */
    public static function fromString(string $status): self
    {
        foreach (self::cases() as $case) {
            if ($status === $case->value) {
                return $case;
            }
        }

        throw new ValidationException("Status '$status' not found");
    }
}
