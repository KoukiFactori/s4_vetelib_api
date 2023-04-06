<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model;
use App\Controller\GetUserAnimals;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/me/animals',
            controller: GetUserAnimals::class,
            security: 'is_granted("ROLE_CLIENT")',
            normalizationContext: ['animal:read:collection'],
            openapi: new Model\Operation(
                summary: 'Get all animals from the current user',
                description: 'Allow the current connected user to get all the animals they have',
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
