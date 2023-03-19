<?php

namespace App\EntityListener;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;



#[AsEntityListener(
    entity: Animal::class,
    event: Events::prePersist
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
        if ($this->security->isGranted('ROLE_CLIENT')){
            if($animal->getClient() === null){
                $animal->setClient($this->security->getUser());
            }
        }
    }
}