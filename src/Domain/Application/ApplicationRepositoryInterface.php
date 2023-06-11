<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Domain\Application;

/**
 * Интерфейс репозитория для работы с заявками
 */
interface ApplicationRepositoryInterface
{
    /**
     * Возвращает массив заявок
     * @param GetListDto $listDto
     * @return ApplicationInterface[]
     */
    public function getList(GetListDto $listDto): array;

    /**
     * Сохраняет новую заявку в репозитории
     * @param CreateDto $dto
     * @return ApplicationInterface
     */
    public function create(CreateDto $dto): ApplicationInterface;
}
