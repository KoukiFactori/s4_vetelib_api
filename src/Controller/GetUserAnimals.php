<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetUserAnimals extends AbstractController
{
    public function __invoke()
    {
        // Client should be connected to pass security restriction and access this controller

        /**
         * @var Client $client
         */
        $client = $this->getUser();

        return $client->getAnimals();
    }
}
