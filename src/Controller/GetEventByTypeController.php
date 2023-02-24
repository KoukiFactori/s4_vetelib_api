<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetEventByTypeController extends AbstractController
{
    public function __invoke(Event $data , EventRepository $e): array
    {
        return $e->getAllEventByType($data->getTypeEvent(), $this->getUser());
    }
}