<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Controller;

use DateTime;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Action\CreateAction;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Action\GetListAction;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\CreateDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\GetListDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\ViewDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Exception\ForbiddenException;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Exception\ValidationException;
use Hemonugi\ToolKitTestAssignment\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
     * @param User|null $user
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/list', name: 'application_list', methods: ['get'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Список заявок',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ViewDto::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'Если пользовать не имеет прав получить запрашиваемый ресурс',
        content: new OA\JsonContent(
            type: 'string',
            example: 'Клиент не может смотреть чужие заявки'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Если переданы невалидные параметры',
        content: new OA\JsonContent(
            type: 'string',
            example: 'Некорректный запрос'
        )
    )]
    #[OA\Parameter(
        name: 'statuses[]',
        description: 'Список статусов',
        in: 'query',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                ApplicationStatus::Open,
                ApplicationStatus::Archived,
                ApplicationStatus::Closed,
            ])
        )
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
    #[OA\Parameter(
        name: 'userId',
        description: 'Фильтр по пользователю',
        in: 'query',
        schema: new OA\Schema(type: 'int')
    )]
    public function index(
        Request $request,
        GetListAction $getListAction,
        ApplicationRepositoryInterface $repository,
        #[CurrentUser] ?User $user,
    ): JsonResponse {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $userId = $request->query->get('userId');

        try {
            $dto = new GetListDto(
                statuses: $request->query->all('statuses'),
                startDateTime: $startDate !== null ? new DateTime($startDate) : null,
                endDateTime: $endDate !== null ? new DateTime($endDate) : null,
                creatorId: $userId !== null ? (int)$userId : null,
            );

            return $this->json($getListAction($dto, $user, $repository));
        } catch (ForbiddenException $e) {
            return $this->json($e->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (ValidationException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @param CreateAction $action
     * @param User|null $user
     * @param ApplicationRepositoryInterface $repository
     * @return JsonResponse
     */
    #[Route('/create', name: 'application_create', methods: ['post'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Список заявок',
        content: new OA\JsonContent(
            ref: new Model(type: ViewDto::class)
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'При отсутствии необходимых параметров',
        content: new OA\JsonContent(
            type: 'string',
            example: 'Отсутствуют необходимые параметры'
        )
    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: "application/x-www-form-urlencoded",
                schema: new OA\Schema(
                    required: ['title', 'text'],
                    properties: [
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "text", type: "string"),
                    ]
                )
            ),
        ],
    )]
    public function create(
        Request $request,
        CreateAction $action,
        #[CurrentUser] ?User $user,
        ApplicationRepositoryInterface $repository,
    ): JsonResponse {
        $title = $request->request->get('title');
        $text = $request->request->get('text');

        if ($title === null || $text === null) {
            return $this->json('Отсутствуют необходимые параметры', Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $action(
                new CreateDto(title: $title, text: $text, creator: $user),
                $repository
            )
        );
    }
}
