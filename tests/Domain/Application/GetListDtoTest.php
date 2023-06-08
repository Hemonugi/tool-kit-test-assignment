<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Domain\Application;

use DateTime;
use Exception;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ValidationException;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class GetListDtoTest extends TestCase
{
    /**
     * Должен бросать исключение, если дата начала больше даты конца.
     * @return void
     */
    public function testShouldThrowExceptionIfStartDateIsLaterThenEndDate(): void
    {
        $this->expectException(ValidationException::class);

        new GetListDto(
            startDateTime: new DateTime('2024-08-06 12:00:00'),
            endDateTime: new DateTime('2023-08-06 12:00:00'),
        );
    }

    /**
     * Если все параметры валидные, то все должно быт ОК
     * @throws ValidationException
     * @throws Exception
     */
    public function testDtoCreationWhenAllParameterAreValid(): void
    {
        $startDate = '2023-08-06 12:00:00';
        $endDate = '2024-08-06 12:00:00';
        $statuses = ['open', 'archived'];

        $dto = new GetListDto(
            statuses: $statuses,
            startDateTime: new DateTime($startDate),
            endDateTime: new DateTime($endDate),
        );

        assertSame($statuses, $dto->statuses);
        assertSame($startDate, $dto->startDateTime?->format('Y-m-d H:i:s'));
        assertSame($endDate, $dto->endDateTime?->format('Y-m-d H:i:s'));
    }
}
