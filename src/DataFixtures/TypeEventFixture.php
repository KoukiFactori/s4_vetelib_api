<?php

namespace App\DataFixtures;

use App\Factory\TypeEventFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeEventFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeEventFile = file_get_contents(__DIR__.'/data/TypeEvent.json', true);
        $typeEvents = json_decode($typeEventFile, true);

        foreach ($typeEvents as $element) {
            TypeEventFactory::createOne($element);
        }
    }
}
