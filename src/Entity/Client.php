<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model;
use App\Controller\GetAllClientOfVeterinaireController;
use App\Controller\GetUserAnimalsController;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/me/animals',
            controller: GetUserAnimalsController::class,
            security: 'is_granted("ROLE_CLIENT")',
            normalizationContext: ['animal:read:collection'],
            openapi: new Model\Operation(
                summary: 'Get all animals from the current user',
                description: 'Allow the current connected user to get all the animals they have',
            )
        ),
    ]
)]
#[ApiResource(
    uriTemplate: '/veterinaire/{id}/clients',
    security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VETERINAIRE")',
    operations: [
        new GetCollection(
            controller: GetAllClientOfVeterinaireController::class,
            openapi: new Model\Operation(
                tags: ['Veterinaire'],
                summary: 'Retrieves all clients related to a veterinaire',
                description: 'Returns an array of all clients related to a veterinaire, clients who have an animal with an event with said veterinaire',
                responses: [
                    '200' => [
                        'description' => 'Success',
                    ],
                    '401' => [
                        'description' => 'Forbidden',
                    ],
                    '403' => [
                        'description' => 'Unauthorized',
                    ],
                    '404' => [
                        'description' => 'Veterinaire not found',
                    ],
                    '500' => [
                        'description' => 'Server error',
                    ],
                ]
            )
        ),
    ]
)]
class Client extends User
{
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Animal::class)]
    private Collection $animals;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setClient($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getClient() === $this) {
                $animal->setClient(null);
            }
        }

        return $this;
    }
}
