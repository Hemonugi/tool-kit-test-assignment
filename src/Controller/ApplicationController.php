<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Controller;

use DateTime;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListAction;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Обработчики запросов для работы с заявками
 */
#[Route('/api/application')]
class ApplicationController extends AbstractController
{
    #[Route('/list', name: 'application_list', methods: ['get'])]
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
