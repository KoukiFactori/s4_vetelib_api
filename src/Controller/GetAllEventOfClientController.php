<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetAllEventOfClientController extends AbstractController
{
    public function __invoke(EventRepository $er , int $id): array
    {
        return $er->getAllEventByClient($this->getDoctrine()->getRepository(User::class)->find($id));
    }
}