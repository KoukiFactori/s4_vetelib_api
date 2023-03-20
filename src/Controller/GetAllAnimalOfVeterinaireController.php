<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAllAnimalOfVeterinaireController extends AbstractController
{
    public function __construct(private AnimalRepository $er, private VeterinaireRepository $vr){}
    public function __invoke(int $id): array
    {
        return $this->er->getAllAnimalByVeterinaire($this->vr->find($id));
    }
}
