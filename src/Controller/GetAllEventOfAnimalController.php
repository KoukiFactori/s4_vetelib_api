<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAllEventOfAnimalController extends AbstractController
{
    private EventRepository $er;
    private AnimalRepository $ar;

    public function __construct(EventRepository $er, AnimalRepository $ar)
    {
        $this->er = $er;
        $this->ar = $ar;
    }

    public function __invoke(int $id): array
    {
        $events = [];
        if ($this->isGranted('ROLE_ADMIN')) {
            $events = $this->er->findEventByAnimal($this->ar->find($id));
        }

        if ($this->isGranted('ROLE_CLIENT')) {
            $animal = $this->ar->find($id);
            if ($animal->getClient()->getId() == $this->getUser()->getId()) {
                $events = $this->er->findEventByAnimal($animal);
            } else {
                throw new AccessDeniedException("Vous n'avez pas accès au rendez vous de cet animal");
            }
        }

        return $events;
    }
}
