<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAllEventAvailableOfVeterinaireController extends AbstractController
{
    public function __construct( private VeterinaireRepository $vr)
    {
    }

    public function __invoke(int $id): array
    {

        return $this->vr->getAvailableSlots($this->vr->find($id));
    }
}
