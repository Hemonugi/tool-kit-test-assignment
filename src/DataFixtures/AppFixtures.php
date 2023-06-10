<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\DataFixtures;

use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Hemonugi\ToolKitTestAssignment\Domain\Application\CreateDto;
use Hemonugi\ToolKitTestAssignment\Entity\Application;
use Symfony\Component\Clock\MockClock;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $date = new DateTimeImmutable('2022-06-16 12:00:00');

        for ($i = 0; $i < 20; $i++) {
            $date = $date->modify('+8 hours');
            $dto = new CreateDto($faker->sentence(), $faker->text());
            $application = Application::create($dto, new MockClock($date));

            $manager->persist($application);
        }

        $manager->flush();
    }
}
