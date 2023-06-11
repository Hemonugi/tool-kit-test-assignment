<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Hemonugi\ToolKitTestAssignment\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationControllerTest extends WebTestCase
{
    private const CLIENT_USER = 'client';
    private const ADMIN_USER = 'admin';

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @return KernelBrowser
     */
    private function createAuthenticatedClient(string $username): KernelBrowser
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'client',
                'password' => '123',
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testClientCanOnlySeeHisOwnApplications(): void
    {
        $client = $this->createAuthenticatedClient(self::CLIENT_USER);
        $client->request('GET', '/api/application/list');

        self::assertResponseIsSuccessful();

        $applications = json_decode($client->getResponse()->getContent(), true);

        foreach ($applications as $application) {
            self::assertSame(self::CLIENT_USER, $application['creator']['nick']);
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testClientShouldNotBeAbleToSeeOtherUsersRequests(): void
    {
        $client = $this->createAuthenticatedClient(self::CLIENT_USER);

        $client->request('GET', '/api/application/list?userId=' . $this->getRandomUserId());

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function getRandomUserId(): int
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        /** @var User $user */
        $user = $entityManager->createQuery('SELECT u FROM ' . User::class . ' u WHERE u.nick NOT IN (:users)')
            ->setParameter(':users', [self::CLIENT_USER, self::ADMIN_USER])
            ->setMaxResults(1)
            ->getSingleResult();

        return $user->getViewDto()->id;
    }
}
