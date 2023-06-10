<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ChangeStatusDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\CreateDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\RegisterDto;
use Hemonugi\ToolKitTestAssignment\Entity\Application;
use Hemonugi\ToolKitTestAssignment\Entity\User;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = FakerFactory::create('ru_RU'); //todo переделать на DIC
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $this->createApplications($manager);

        $manager->flush();
    }

    /**
     * Добавляет заявки в БД
     * @param ObjectManager $manager
     * @return void
     */
    public function createApplications(ObjectManager $manager): void
    {
        $date = new DateTimeImmutable('2022-06-16 12:00:00');

        $statuses = ApplicationStatus::cases();
        $j = 0;

        for ($i = 0; $i < 20; $i++) {
            $date = $date->modify('+8 hours');
            $dto = new CreateDto(
                $this->faker->sentence(),
                $this->faker->text()
            );
            $application = Application::create($dto, new MockClock($date));

            $status = $statuses[$j];

            $statusDto = new ChangeStatusDto($status);
            $application->changeStatus($statusDto);

            $j++;
            if ($j >= count($statuses)) {
                $j = 0;
            }

            $manager->persist($application);
        }
    }

    /**
     * Добавляет пользователей в БД
     * @param ObjectManager $manager
     * @return void
     */
    private function createUsers(ObjectManager $manager): void
    {
        $roles = [User::ROLE_CLIENT, User::ROLE_ADMIN];

        for ($i = 0; $i < 4; $i++) {
            $dto = new RegisterDto(
                $this->faker->userName(),
                $this->faker->phoneNumber(),
                $this->faker->address(),
            );
            $user = User::createUser($dto);
            $user->setPassword($this->passwordHasher->hashPassword($user, '123'));
            $user->setRoles($roles);
            $manager->persist($user);

            $roles = [User::ROLE_CLIENT];
        }
    }
}
