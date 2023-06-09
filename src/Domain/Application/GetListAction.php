<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

/**
 * Получение списка заявок
 */
final class GetListAction
{
    /**
     * @param GetListDto $requestListDto
     * @param ApplicationRepositoryInterface $repository
     * @return ViewDto[]
     */
    public function __invoke(GetListDto $requestListDto, ApplicationRepositoryInterface $repository): array
    {
        return array_map(
            fn(ApplicationInterface $application) => $application->getViewDto(),
            $repository->getList($requestListDto)
        );
    }
}
