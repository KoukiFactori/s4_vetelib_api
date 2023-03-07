<?php

namespace App\DataFixtures;

use App\Factory\AdminFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AdminFactory::createOne(['firstname' => 'Nicolas', 'lastname' => 'Mossmann', 'email' => 'nicolas.mossmann@etudiant.univ-reims.fr']);
    }
}
