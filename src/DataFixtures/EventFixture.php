<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
use App\Factory\VeterinaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Tableau stoquant les évennements déjà présents
        $datetimes = [];

        EventFactory::createMany(2000, function () use (&$datetimes){

            $veterinaire = VeterinaireFactory::random();
            $vetoId = $veterinaire->getId();
            if(!isset($datetimes[$vetoId])) $datetimes[$vetoId] = [];
            
            // Tant que les évennements se chevauchent et ne sont pas en weekend, on regénère une nouvelle date
            do {                
                $start = EventFactory::faker()->dateTimeBetween('-15 days', '+15 days');
                $start->setTime(
                    EventFactory::faker()->numberBetween(8, 18),
                    EventFactory::faker()->boolean() ? 0 : 30
                );
                
                $end = clone $start;
                $end->modify('+30 minutes');
                
                $regenerate = false;
                foreach ($datetimes[$vetoId] as $datetime) {
                    if ($start >= $datetime['start'] && $start < $datetime['end']) {
                        $regenerate = true;
                    }
                    else if ($end > $datetime['start'] && $end <= $datetime['end']) {
                        $regenerate = true;
                    }
                    else if (intval($start->format('N')) >= 6) {
                        $regenerate = true;
                    }
                }
            } while ($regenerate);
            
            $datetimes[$vetoId][] = [
                'start' => $start,
                'end' => $end,
            ];

            return [
                'typeEvent' => TypeEventFactory::random(),
                'date' => $start,
                'veterinaire' => $veterinaire
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            TypeEventFixture::class,
            VeterinaireFixture::class
        ];
    }
}
