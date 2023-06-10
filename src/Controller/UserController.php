<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Controller;

use Hemonugi\ToolKitTestAssignment\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = '...';

        return $this->json([
            'user' => $user->getViewDto(),
            'token' => $token,
        ]);
    }
}
