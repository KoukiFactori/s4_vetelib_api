<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GetAllEventOfVeterinaireController extends AbstractController
{
    public function __construct(private EventRepository $er, private VeterinaireRepository $vr){}
    public function __invoke(int $id): array
    {
        if ($this->getUser()->getId()!= $id) {
            throw new AccessDeniedException("Vous n'avez pas accès au rendez vous de ce vétérinaire");
        }
        return $this->er->getAllEventByVeterinaire($this->vr->find($id));
    }
}
