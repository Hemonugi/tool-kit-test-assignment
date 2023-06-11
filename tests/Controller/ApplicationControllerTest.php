<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

        $this->assertResponseIsSuccessful();

        $applications = json_decode($client->getResponse()->getContent(), true);

        foreach ($applications as $application) {
            self::assertSame(self::CLIENT_USER, $application['creator']['nick']);
        }
    }
}
