<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetAllEventOfClientController extends AbstractController
{
    private EventRepository $er;
    private UserRepository $ur;

    public function __construct(EventRepository $er, UserRepository $ur)
    {
        $this->er = $er;
        $this->ur = $ur;
    }
    public function __invoke( int $id): array
    {
        return $this->er->getAllEventByClient($this->ur->find($id));
    }
}