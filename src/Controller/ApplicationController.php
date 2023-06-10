<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Controller;

use DateTime;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListAction;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ValidationException;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ViewDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

/**
 * Обработчики запросов для работы с заявками
 */
#[Route('/api/application')]
class ApplicationController extends AbstractController
{
    /**
     * @param Request $request
     * @param GetListAction $getListAction
     * @param ApplicationRepositoryInterface $repository
     * @return JsonResponse
     * @throws ValidationException
     */
    #[Route('/list', name: 'application_list', methods: ['get'])]
    #[OA\Response(
        response: 200,
        description: 'Список заявок',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ViewDto::class))
        )
    )]
    #[OA\Parameter(
        name: 'statuses[]',
        description: 'Список статусов',
        in: 'query',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'startDate',
        description: 'Заявки после этой даты будут попадать в выборку',
        in: 'query',
        schema: new OA\Schema(type: 'date')
    )]
    #[OA\Parameter(
        name: 'endDate',
        description: 'Заявки до этой даты будут попадать в выборку',
        in: 'query',
        schema: new OA\Schema(type: 'date')
    )]
    public function index(
        Request $request,
        GetListAction $getListAction,
        ApplicationRepositoryInterface $repository,
    ): JsonResponse {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $dto = new GetListDto(
            statuses: $request->query->all('statuses'),
            startDateTime: $startDate !== null ? new DateTime($startDate) : null,
            endDateTime: $endDate !== null ? new DateTime($endDate) : null,
        );

        return $this->json($getListAction($dto, $repository));
    }
}
