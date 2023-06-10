<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\User;

readonly final class RegisterUserDto
{
    public function __construct(
        public string $nick,
        public string $phone,
        public string $address,
    ) {
    }
}
