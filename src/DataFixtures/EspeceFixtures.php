<?php

namespace App\DataFixtures;

use App\Factory\EspeceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EspeceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $especeFile = file_get_contents(__DIR__.'/data/Espece.json', true);
        $especes = json_decode($especeFile, true);

        foreach ($especes as $element) {
            EspeceFactory::createOne($element);
        }
    }
}
