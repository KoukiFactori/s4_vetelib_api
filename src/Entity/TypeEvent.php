<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\TypeEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeEventRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(
            uriTemplate: '/typeEvents',
            security: "is_granted('ROLE_USER')",
            openapiContext: [
                'summary' => 'Récupérer la collection des types d\'évènements',
                'description' => 'Récupérer la collection des types d\'évènements',
            ]
        ),
        new Get(
            uriTemplate: '/typeEvents/{id}',
            paginationEnabled: false,
            security: "is_granted('ROLE_USER')",
            openapiContext: [
                'summary' => 'Get One Type Event',
                'description' => 'Get one type event',
                'responses' => [
                    '200' => [
                        'description' => 'Recovery of the type event by its id',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The type event does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the type event',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
            ]
        ),
        new Post(
            uriTemplate: '/typeEvents',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Create a type event',
                'description' => 'Create a type event',
                'responses' => [
                    '201' => [
                        'description' => 'The type event has been created',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'libType' => [
                                        'type' => 'string',
                                        'example' => 'Euthanasie',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ),
        new Patch(
            uriTemplate: '/typeEvents/{id}',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Update a type event',
                'description' => 'Update a type event',
                'responses' => [
                    '200' => [
                        'description' => 'The type event has been updated',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The type event does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the type event',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'libType' => [
                                        'type' => 'string',
                                        'example' => 'Euthanasie',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ),
        new Put(
            uriTemplate: '/typeEvents/{id}',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Update a type event',
                'description' => 'Update a type event',
                'responses' => [
                    '200' => [
                        'description' => 'The type event has been updated',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The type event does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the type event',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'libType' => [
                                        'type' => 'string',
                                        'example' => 'Euthanasie',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ),
        
        
        
        
],
)]
class TypeEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libType = null;

    #[ORM\OneToMany(mappedBy: 'typeEvent', targetEntity: Event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibType(): ?string
    {
        return $this->libType;
    }

    public function setLibType(string $libType): self
    {
        $this->libType = $libType;

        return $this;
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
            $event->setTypeEvent($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getTypeEvent() === $this) {
                $event->setTypeEvent(null);
            }
        }

        return $this;
    }
}
