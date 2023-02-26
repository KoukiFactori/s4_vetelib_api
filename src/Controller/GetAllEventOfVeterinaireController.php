<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetAllEventOfVeterinaireController extends AbstractController
{
    private EventRepository $er;
    private VeterinaireRepository $vr;

    public function __construct(EventRepository $er, VeterinaireRepository $vr)
    {
        $this->er = $er;
        $this->vr = $vr;
    }
    public function __invoke(int $id): array
    {
        return $this->er->getAllEventByVeterinaire($this->vr->find($id));
    }
}
