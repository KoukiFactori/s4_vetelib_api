<?php

namespace App\Controller;

use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAllClientOfVeterinaireController extends AbstractController
{
    public function __construct(
        private Security $security,
        private VeterinaireRepository $vr
    ) {
    }

    public function __invoke(int $id): array
    {
        if ($this->security->isGranted('ROLE_VETERINAIRE') && $this->getUser()->getId() !== $id) {
            throw new AccessDeniedException("Vous n'avez pas les permissions nécessaires pour accéder à cette ressource");
        }

        return $this->vr->retrieveAllClientRelatedToVeterinaire($id);
    }
}
