<?php

namespace App\DataFixtures;

use App\Factory\VeterinaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VeterinaireFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        VeterinaireFactory::createOne(['firstname' => 'Antoine', 'lastname' => 'Maréchal', 'email' => 'antoinemarechal08@gmail.com']);
        VeterinaireFactory::createMany(5);
    }
}
