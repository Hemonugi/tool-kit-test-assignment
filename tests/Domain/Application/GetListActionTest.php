<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Domain\Application;

use DateTimeInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListAction;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ViewDto;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\once;

class GetListActionTest extends TestCase
{
    /**
     * Должен возвращать данные из репозитория
     * @return void
     */
    public function testShouldLoadApplicationsFromRepository(): void
    {
        $dto = new GetListDto();
        $result = [
            $this->createApplication(1, 'test', 'test', new \DateTime(), 'open'),
            $this->createApplication(2, 'test 2', 'test 2', new \DateTime(), 'closed'),
        ];

        $repository = $this->createMock(ApplicationRepositoryInterface::class);

        $repository->expects(once())
            ->method('getList')
            ->with($dto)
            ->willReturn($result);

        $list = (new GetListAction())($dto, $repository);

        assertSame('test', $list[0]->title);
        assertSame('test 2', $list[1]->title);
    }

    private function createApplication(
        int $id,
        string $title,
        string $text,
        DateTimeInterface $dateTime,
        string $status
    ): ApplicationInterface {
        $application = $this->createMock(ApplicationInterface::class);
        $application->method('getViewDto')
            ->willReturn(new ViewDto($id, $title, $text, $dateTime, $status));

        return $application;
    }
}
