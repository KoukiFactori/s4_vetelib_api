<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetAllEventOfVeterinaireController extends AbstractController
{
    public function __invoke(EventRepository $er): array
    {
        return $er->getAllEventByVeterinaire($this->getUser());
    }
}