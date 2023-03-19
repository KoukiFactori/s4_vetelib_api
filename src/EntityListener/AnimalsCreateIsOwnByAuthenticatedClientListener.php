<?php

namespace App\EntityListener;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;



#[AsEntityListener(
    events: Events::prePersist,
    entity: Animal::class
)]
class AnimalsCreateIsOwnByAuthenticatedClientListener
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->security = $security;
        $this->em = $em;
    }
    public function prePersist(Animal $animal): void
    {     
        if ($this->security->isGranted('ROLE_CLIENT')) {
            if ($this->security->getUser()->getId() != $animal->getClient()->getId()) {

                throw new AccessDeniedException();
            }
        }
    }
}