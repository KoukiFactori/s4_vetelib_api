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
            if ($this->security->getUser()->getId() != $event->getAnimal()->getClient()->getId()) {

                throw new AccessDeniedException();
            }
        }

        if ($this->security->isGranted('ROLE_VETERINAIRE')) {
            if($event->getVeterinaire() == null)
            {
                $event->setVeterinaire($this->security->getUser());
            }
            if ($this->security->getUser()->getId() != $event->getVeterinaire()->getId()) {
 
                throw new AccessDeniedException();
            }
        }
    }
}


