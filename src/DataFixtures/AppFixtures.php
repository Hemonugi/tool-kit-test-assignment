<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\ChangeStatusDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\CreateDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\Dto\RegisterDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\UserInterface;
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
        $users = $this->createUsers($manager);
        $this->createApplications($manager, $users);

        $manager->flush();
    }

    /**
     * Добавляет заявки в БД
     * @param ObjectManager $manager
     * @param UserInterface[] $users
     * @return void
     */
    public function createApplications(ObjectManager $manager, array $users): void
    {
        $date = new DateTimeImmutable('2022-06-16 12:00:00');

        $statuses = ApplicationStatus::cases();
        $statusIndex = 0;
        $userIndex = 0;

        for ($i = 0; $i < 20; $i++) {
            $date = $date->modify('+8 hours');
            $dto = new CreateDto(
                $this->faker->sentence(),
                $this->faker->text(),
                $users[$userIndex],
            );
            $application = Application::create($dto, new MockClock($date));

            $status = $statuses[$statusIndex];

            $statusDto = new ChangeStatusDto($status);
            $application->changeStatus($statusDto);

            $statusIndex++;
            if ($statusIndex >= count($statuses)) {
                $statusIndex = 0;
            }

            $userIndex++;
            if ($userIndex >= count($users)) {
                $userIndex = 0;
            }

            $manager->persist($application);
        }
    }

    /**
     * Добавляет пользователей в БД
     * @param ObjectManager $manager
     * @return UserInterface[] Список пользователей без роли админка
     */
    private function createUsers(ObjectManager $manager): array
    {
        $roles = [User::ROLE_CLIENT, User::ROLE_ADMIN];

        $result = [];

        for ($i = 0; $i < 5; $i++) {
            $dto = new RegisterDto(
                $this->createNick($i),
                $this->faker->phoneNumber(),
                $this->faker->address(),
            );
            $user = User::createUser($dto);
            $user->setPassword($this->passwordHasher->hashPassword($user, '123'));
            $user->setRoles($roles);
            $manager->persist($user);

            if (!in_array(User::ROLE_ADMIN, $user->getRoles(), true)) {
                $result[] = $user;
            }

            $roles = [User::ROLE_CLIENT];
        }

        return $result;
    }

    /**
     * Создает имя пользователя в соответствии с порядковым номером
     * @param int $i
     * @return string
     */
    private function createNick(int $i): string
    {
        return match ($i) {
            0 => 'admin',
            1 => 'client',
            default => $this->faker->userName(),
        };
    }
}
