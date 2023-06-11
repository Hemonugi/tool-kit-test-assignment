<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

use Hemonugi\ToolKitTestAssignment\Domain\User\UserInterface;
use Hemonugi\ToolKitTestAssignment\Entity\User;

/**
 * Получение списка заявок
 */
final class GetListAction
{
    /**
     * @param GetListDto $requestListDto
     * @param UserInterface $creator
     * @param ApplicationRepositoryInterface $repository
     * @return ViewDto[]
     * @throws ValidationException
     * @throws ForbiddenException
     */
    public function __invoke(
        GetListDto $requestListDto,
        UserInterface $creator,
        ApplicationRepositoryInterface $repository
    ): array {
        if (!$creator->hasRole(User::ROLE_ADMIN)) {
            if ($requestListDto->creatorId === null) {
                $requestListDto = new GetListDto(
                    statuses: $requestListDto->statuses,
                    startDateTime: $requestListDto->startDateTime,
                    endDateTime: $requestListDto->endDateTime,
                    creatorId: $creator->getViewDto()->id
                );
            }

            if ($requestListDto->creatorId !== $creator->getViewDto()->id) {
                throw new ForbiddenException('Клиенты не могут смотреть чужие заявки');
            }
        }

        return array_map(
            fn(ApplicationInterface $application) => $application->getViewDto(),
            $repository->getList($requestListDto)
        );
    }
}
