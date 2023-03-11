<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Event::class
)]
class EventAnimalIsOwnByAuthenticatedClientListener
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->security = $security;
        $this->em = $em;
    }
    public function prePersist(Event $event): void
    {   
        if ($this->security->isGranted('ROLE_CLIENT')) {
            dd('ici client 1');
            if ($this->security->getUser()->getId() != $event->getAnimal()->getClient()->getId()) {
                dd('ici client 2');
                throw new AccessDeniedException("Vous ne pouvez pas creer ce rendez-vous , vous n'etes pas le propriétaire de l'animal");
            }
        }

        if ($this->security->isGranted('ROLE_VETERINAIRE')) {
            dd('ici veterinaire 1');
            if ($this->security->getUser()->getId() != $event->getVeterinaire()->getId()) {
                dd('ici veterinaire 2');
                throw new AccessDeniedException("Vous ne pouvez pas creer ce rendez-vous , vous n'etes pas le veterinaire concerné");
            }
        }
    }
}


