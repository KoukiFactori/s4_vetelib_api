<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\VeterinaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\OpenApi\Model;

#[ORM\Entity(repositoryClass: VeterinaireRepository::class)]
#[ApiResource(
    #Route should not have security. Everyone need the possibility to search for veterinarian contact info.
    normalizationContext: [
        'groups' => ['veterinaire:read']
    ],
    operations: [
        new Get(
            uriTemplate: '/veterinaires/{id}',
            requirements: [
                'id' => '\d+'
            ],
            openapi: new Model\Operation(
                summary: 'Retrieves a specific veterinarian',
                description: 'Retrieves a specific veterinarian given his user identifier',
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'User Identifier',
                        required: true,
                        schema: [
                            'type' => 'integer'
                        ]
                    )
                ]
            )
        ),
        new GetCollection(
            openapi: new Model\Operation(
                summary: 'Retrieves all the veterinarian',
                description: 'Retrieves all the veterinarian, with limited informations for contact purpose'
            )
        )
    ]
)]
class Veterinaire extends User
{
    #[ORM\OneToMany(mappedBy: 'veterinaire', targetEntity: Event::class)]
    
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setVeterinaire($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getVeterinaire() === $this) {
                $event->setVeterinaire(null);
            }
        }

        return $this;
    }
}
