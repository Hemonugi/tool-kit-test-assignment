<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

/**
 * Экшен создания новой заявки
 */
class CreateAction
{
    /**
     * @param CreateDto $dto
     * @param ApplicationRepositoryInterface $repository
     * @return ViewDto
     */
    public function __invoke(CreateDto $dto, ApplicationRepositoryInterface $repository): ViewDto
    {
        return $repository->create($dto)->getViewDto();
    }
}
