<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Domain\Application;

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
            new ViewDto(1, 'test', 'test', new \DateTime(), 'open'),
            new ViewDto(2, 'test 2', 'test 2', new \DateTime(), 'closed'),
        ];

        $repository = $this->createMock(ApplicationRepositoryInterface::class);

        $repository->expects(once())
            ->method('getList')
            ->with($dto)
            ->willReturn($result);

        $list = (new GetListAction())($dto, $repository);

        assertSame($result, $list);
    }
}
