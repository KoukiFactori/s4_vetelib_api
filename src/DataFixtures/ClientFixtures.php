<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ClientFactory::createOne(['firstname' => 'Simon', 'lastname' => 'Ledoux', 'email' => 'simon@simon511000.fr']);
        ClientFactory::createMany(50);
    }
}
