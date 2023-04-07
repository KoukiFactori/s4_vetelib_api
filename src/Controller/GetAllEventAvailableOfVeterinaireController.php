<?php

namespace App\Controller;

use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetAllEventAvailableOfVeterinaireController extends AbstractController
{
    public function __construct( private VeterinaireRepository $vr)
    {
    }

    public function __invoke(int $id , string $date): array
    {

        return $this->vr->getAvailableSlots($this->vr->find($id) , $date);
    }
}
