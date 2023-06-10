<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Hemonugi\ToolKitTestAssignment\Domain\Application\CreateDto;
use Hemonugi\ToolKitTestAssignment\Entity\Application;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 20; $i++) {
            $dto = new CreateDto($faker->sentence(), $faker->text());
            $application = Application::create($dto);

            $manager->persist($application);
        }

        $manager->flush();
    }
}
