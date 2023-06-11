<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Domain\User;

use Exception;
use Hemonugi\ToolKitTestAssignment\Domain\User\RegisterDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\UserInterface;
use Hemonugi\ToolKitTestAssignment\Domain\User\ViewDto;

readonly class UserStub implements UserInterface
{
    public function __construct(private int $id = 11, private array $roles = [])
    {
    }

    public function getViewDto(): ViewDto
    {
        return new ViewDto(
            $this->id,
            'lkopylova',
            '(812) 541-34-64',
            '561495, Тамбовская область, город Ногинск, ул. Гагарина, 49'
        );
    }

    /**
     * @throws Exception
     */
    public static function createUser(RegisterDto $registerUserDto): UserInterface
    {
        throw new Exception('не реализован для тестов');
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }
}
