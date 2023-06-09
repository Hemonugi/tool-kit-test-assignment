<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

interface ApplicationRepositoryInterface
{
    /**
     * @param GetListDto $listDto
     * @return ApplicationInterface[]
     */
    public function getList(GetListDto $listDto): array;
}
