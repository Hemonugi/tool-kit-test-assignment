<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\User\Dto;

readonly final class RegisterDto
{
    public function __construct(
        public string $nick,
        public string $phone,
        public string $address,
    ) {
    }
}
