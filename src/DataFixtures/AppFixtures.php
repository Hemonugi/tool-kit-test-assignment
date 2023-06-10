<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ChangeStatusDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\CreateDto;
use Hemonugi\ToolKitTestAssignment\Entity\Application;
use Symfony\Component\Clock\MockClock;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('ru_RU');

        $date = new DateTimeImmutable('2022-06-16 12:00:00');

        $statuses = ApplicationStatus::cases();
        $j = 0;

        for ($i = 0; $i < 20; $i++) {
            $date = $date->modify('+8 hours');
            $dto = new CreateDto($faker->sentence(), $faker->text());
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

        $manager->flush();
    }
}
