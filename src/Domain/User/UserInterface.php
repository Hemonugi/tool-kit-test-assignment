<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\User;

use Hemonugi\ToolKitTestAssignment\Domain\User\Dto\RegisterDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\Dto\ViewDto;

interface UserInterface
{
    /**
     * Возвращает представление пользователя
     * @return ViewDto
     */
    public function getViewDto(): ViewDto;

    /**
     * Создает пользователя
     * @param RegisterDto $registerUserDto
     * @return self
     */
    public static function createUser(RegisterDto $registerUserDto): self;

    /**
     * Имеет ли пользователь указанную роль
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool;
}
