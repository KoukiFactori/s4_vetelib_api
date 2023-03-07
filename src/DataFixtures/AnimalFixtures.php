<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        AnimalFactory::createMany(150, function () {
            return ['espece' => EspeceFactory::random(),
            'client' => ClientFactory::random()];
        });
    }

    public function getDependencies(): array
    {
        return [
            EspeceFixtures::class,
            ClientFixtures::class,
        ];
    }
}
