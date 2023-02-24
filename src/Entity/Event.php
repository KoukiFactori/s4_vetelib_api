<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use app\Controller\GetEventByTypeController;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use APiPlatform\Metadata\Get;
use APiPlatform\Metadata\Post;
use APiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(
                uriTemplate:'/events/type/{id}',
                controller: GetEventByTypeController::class,
                security:'is_granted("ROLE_VETERINAIRE") and object.veterinaire == user',
                openapiContext:
                [
                    'summary' => 'Get collection of events of the same type',
                    'description' => 'Get all events by type',
                    'response' =>['200' , '401', '403', '404'],
                    'parameters' => [
                        'id' => [
                            'name' => 'id',
                            'in' => 'path',
                            'description' => 'The type of event 1 for non urgent 2 for urgent',
                            'type' => 'integer',
                            'required' => true,
                            'openapi' => [
                                'example' => 1
                            ]
                        ]
                    ],
        
                ]
                ),
        new Get(
            uriTemplate:'/events/{id}',
            paginationEnabled:false,
            security:'is_granted("ROLE_USER") and (object.user = user or object.veterinaire = user)',
            openapiContext:
            [
                'summary' => 'Get one events',
                'description' => 'Get event by id',
                'response' =>['200' , '401', '403', '404'],
                'parameters' => [
                    'id' => [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the event we want to get',
                        'type' => 'integer',
                        'required' => true,
                        'openapi' => [
                            'example' => 1
                        ]
                    ]
                ],

            ]
            ),
        new GetCollection(
            uriTemplate:'/events',
            security:'is_granted("ROLE_VETERINAIRE")',
            openapiContext:
            [
                'summary' => 'Get all events',
                'description' => 'Get all events of a veterinaire',
                'response' =>['200' , '401', '403', '404'],
            ],
            controller: GetAllEventOfVeterinaireController::class,
            ),
        new Post(
            uriTemplate:'/events',
            security:'is_granted("ROLE_USER") and (object.user = user or object.veterinaire = user)',
            openapiContext:
                [
                    'summary' => 'Create an event',
                    'description' => 'Create an event',
                    'response' =>['200' , '401', '403', '404'],
                    
                ],

            
            ),
        new Patch(
            uriTemplate:'/events/{id}',
            security:'is_granted("ROLE_USER") and (object.user = user or object.veterinaire = user)',
            openapiContext:
                [
                    'summary' => 'Update an event',
                    'description' => 'Update an event',
                    'response' =>['200' , '401', '403', '404'],
                    'parameters' => [
                        'id' => [
                            'name' => 'id',
                            'in' => 'path',
                            'description' => 'The id of the event you want to update',
                            'type' => 'integer',
                            'required' => true,
                            'openapi' => [
                                'example' => 1
                            ]
                        ]
                    ],
                ]

                ),
        new Delete(
            uriTemplate:'/events/{id}',
            security:'is_granted("ROLE_USER") and (object.user = user or object.veterinaire = user)',
            openapiContext:
                [
                    'summary' => 'Delete an event',
                    'description' => 'Delete an event',
                    'response' =>['200' , '401', '403', '404'],
                    'parameters' => [
                        'id' => [
                            'name' => 'id',
                            'in' => 'path',
                            'description' => 'The id of the event you want to delete',
                            'type' => 'integer',
                            'required' => true,
                            'openapi' => [
                                'example' => 1
                            ]
                        ]
                    ],
                ]
                ),
        new Put(
            uriTemplate:'/events/{id}',
            security:'is_granted("ROLE_USER") and (object.user = user or object.veterinaire = user)',
            openapiContext:
                [
                    'summary' => 'Update an event',
                    'description' => 'Update an event',
                    'response' =>['200' , '401', '403', '404'],
                    'parameters' => [
                        'id' => [
                            'name' => 'id',
                            'in' => 'path',
                            'description' => 'The id of the event you want to update',
                            'type' => 'integer',
                            'required' => true,
                            'openapi' => [
                                'example' => 1
                            ]
                        ]
                    ],
                ]
        )
                     
            ]        
)]

class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_event'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_event'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_event'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]

    private ?TypeEvent $typeEvent = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_event'])]
    private ?Veterinaire $veterinaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getTypeEvent(): ?TypeEvent
    {
        return $this->typeEvent;
    }

    public function setTypeEvent(?TypeEvent $typeEvent): self
    {
        $this->typeEvent = $typeEvent;

        return $this;
    }

    public function getVeterinaire(): ?Veterinaire
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?Veterinaire $veterinaire): self
    {
        $this->veterinaire = $veterinaire;

        return $this;
    }
}
