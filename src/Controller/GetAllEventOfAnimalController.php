<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAllEventOfAnimalController extends AbstractController
{
    public function __construct(private EventRepository $er, private AnimalRepository $ar)
    {
    }

    public function __invoke(int $id): array
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->er->findEventByAnimal($this->ar->find($id));
        }

        if ($this->isGranted('ROLE_CLIENT')) {
            $animal = $this->ar->find($id);

            if ($animal->getClient()->getId() !== $this->getUser()->getId()) {
                throw new AccessDeniedException("Vous n'avez pas accès au rendez vous de cet animal");
            }

            return $this->er->findEventByAnimal($this->ar->find($id));
        }

        if ($this->isGranted('ROLE_VETERINAIRE')) {
            $events = $this->er->findEventByAnimal($this->ar->find($id));
            $eventsFiltered = [];
            
            foreach ($events as $event) {
                if ($event->getVeterinaire()->getId() == $this->getUser()->getId()) {
                    $eventsFiltered[] = ($event);
                }
            }

            return $eventsFiltered;
        }
    }
}
