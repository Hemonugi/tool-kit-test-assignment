<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Domain\Application;

use DateTime;
use DateTimeInterface;
use Exception;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ValidationException;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNull;
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
     * PDO должно создаваться без ошибок
     * @dataProvider dtoCreationDataProvider
     * @param ApplicationStatus[]|null $statuses
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @throws ValidationException
     */
    public function testDtoCreation(?array $statuses, ?DateTimeInterface $startDate, ?DateTimeInterface $endDate): void
    {
        new GetListDto(
            statuses: $statuses,
            startDateTime: $startDate,
            endDateTime: $endDate,
        );

        $this->expectNotToPerformAssertions();
    }

    public function dtoCreationDataProvider(): array
    {
        return [
            'Если все параметры валидные, то все должно быт ОК' => [
                'statuses' => [ApplicationStatus::Open, ApplicationStatus::Archived],
                'startDate' => new DateTime('2023-08-06 12:00:00'),
                'endDate' => new DateTime('2024-08-06 12:00:00'),
            ],
            'Dto без параметров должно создаваться нормально' => [
                'statuses' => null,
                'startDate' => null,
                'endDate' => null,
            ],
            'Когда указана только дата начала' => [
                'statuses' => null,
                'startDate' => new DateTime('2023-08-06 12:00:00'),
                'endDate' => null,
            ],
            'Когда указана только дата конца' => [
                'statuses' => null,
                'startDate' => null,
                'endDate' => new DateTime('2024-08-06 12:00:00'),
            ],
        ];
    }
}
