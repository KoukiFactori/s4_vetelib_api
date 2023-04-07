<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class GetUserAnimalsController extends AbstractController
{
    public function __construct(
        private Security $security
    ) {
    }

    public function __invoke()
    {
        if (!$this->security->isGranted('ROLE_CLIENT')) {
            return [];
        }

        // Client should be connected to pass security restriction and access this controller
        /**
         * @var Client $client
         */
        $client = $this->getUser();

        return $client->getAnimals();
    }
}
