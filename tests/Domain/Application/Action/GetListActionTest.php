<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Domain\Application\Action;

use DateTimeInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Action\GetListAction;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\GetListDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\ViewDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Exception\ForbiddenException;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Exception\ValidationException;
use Hemonugi\ToolKitTestAssignment\Domain\User\UserInterface;
use Hemonugi\ToolKitTestAssignment\Entity\User;
use Hemonugi\ToolKitTestAssignment\Tests\Domain\User\UserStub;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class GetListActionTest extends TestCase
{
    /**
     * Должен возвращать данные из репозитория
     * @return void
     * @throws ForbiddenException
     * @throws ValidationException
     */
    public function testShouldLoadApplicationsFromRepository(): void
    {
        $dto = new GetListDto();
        $result = [
            $this->createApplication(1, 'test', 'test', new \DateTime(), ApplicationStatus::Open),
            $this->createApplication(2, 'test 2', 'test 2', new \DateTime(), ApplicationStatus::Closed),
        ];

        $repository = $this->createMock(ApplicationRepositoryInterface::class);

        $repository->expects(once())
            ->method('getList')
            ->with($dto)
            ->willReturn($result);

        $list = (new GetListAction())($dto, new UserStub(roles: [User::ROLE_ADMIN]), $repository);

        assertSame('test', $list[0]->title);
        assertSame('test 2', $list[1]->title);
    }

    /**
     * Если пользователь с ролью клиент пытается посмотреть чужие заявки, то кидаем ForbiddenException
     * @return void
     * @throws ValidationException
     */
    public function testShouldThrowForbiddenExceptionIfClientUserIsTryingToViewApplicationsFromDifferentClients(): void
    {
        $currentUser = new UserStub(11);
        $dto = new GetListDto(creatorId: 55);

        $repository = $this->createMock(ApplicationRepositoryInterface::class);

        $repository->expects(never())
            ->method('getList');

        $this->expectException(ForbiddenException::class);

        (new GetListAction())($dto, $currentUser, $repository);
    }

    /**
     * Пользователь с ролью клиент видит только свои заявки, если в фильтре не указан пользователь
     * @return void
     * @throws ValidationException
     * @throws ForbiddenException
     */
    public function testClientUserShouldOnlyGetHisOwnApplicationsByDefault(): void
    {
        $clientUser = new UserStub(roles: [User::ROLE_CLIENT]);
        $dto = new GetListDto();

        $repository = $this->createMock(ApplicationRepositoryInterface::class);

        $repository->expects(once())
            ->method('getList')
            ->with(new GetListDto(creatorId: $clientUser->getViewDto()->id));

        (new GetListAction())($dto, $clientUser, $repository);
    }

    private function createApplication(
        int $id,
        string $title,
        string $text,
        DateTimeInterface $dateTime,
        ApplicationStatus $status
    ): ApplicationInterface {
        $application = $this->createMock(ApplicationInterface::class);
        $application->method('getViewDto')
            ->willReturn(
                new ViewDto(
                    $id,
                    $title,
                    $text,
                    $dateTime,
                    $status,
                    $this->createMock(UserInterface::class)
                )
            );

        return $application;
    }
}
