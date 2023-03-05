<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Controller\GetAllEventOfClientController;
use App\Controller\GetAllEventOfAnimalController;
use App\Controller\GetAllEventOfVeterinaireController;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use APiPlatform\Metadata\Get;
use APiPlatform\Metadata\Post;
use APiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(

    operations:[
        new GetCollection(
                uriTemplate:'/events',
                security:'is_granted("ROLE_ADMIN")',
                openapiContext:
                [
                    'summary' => 'Get collection of events of the same type',
                    'description' => 'Get all events by type',
                    'response' =>['200' , '401', '403', '404'],
                    'parameters' => [
                        'libType' => [
                            'name' => 'libType',
                            'in' => 'typeEvent.getLibType()',
                            'description' => 'The type of the event we want to get  (Urgent, Non Urgent)',
                            'type' => 'string',
                            'required' => false,
                            'openapi' => [
                                'example' => 'Urgent'
                            ]
                        ]
                    ],
        
                ]
                ),
        new Get(
            uriTemplate:'/events/{id}',
            paginationEnabled:false,
            security:'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user)',
            openapiContext:
            [
                'summary' => 'Get one events',
                'description' => 'Get event by id',
                'responses' =>['200' , '401', '403', '404'],
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
        new Post(
            uriTemplate:'/events',
            security:'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user)',
            openapiContext:
                [
                    'summary' => 'Create an event',
                    'description' => 'Create an event',
                    'responses' =>['200' , '401', '403', '404'],
                    
                ],

            
            ),
        new Patch(
            uriTemplate:'/events/{id}',
            security:'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user)',
            openapiContext:
                [
                    'summary' => 'Update an event',
                    'description' => 'Update an event',
                    'responses' =>['200' , '401', '403', '404'],
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
            security:'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user)',
            openapiContext:
                [
                    'summary' => 'Delete an event',
                    'description' => 'Delete an event',
                    'responses' =>['200' , '401', '403', '404'],
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
            security:'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user)',
            openapiContext:
                    [
                        'summary' => 'Update an event',
                        'description' => 'Update an event',
                        'responses' =>['200' , '401', '403', '404'],
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
#[ApiResource(
    uriTemplate:'/animals/{id}/events',
    uriVariables: ['id'=> new Link(
        fromClass: Animal::class,
        fromProperty: 'events',
    )],
    openapiContext:[
        'tags' => ['Animal']
    ],
    operations:
    [
        new GetCollection(
            uriTemplate:'/animals/{id}/events',
            security:'is_granted("ROLE_USER") or is_granted("ROLE_ADMIN")',
            controller:GetAllEventOfAnimalController::class,  
            ),
])]
#[ApiResource(
    uriTemplate:'/veterinaires/{id}/events',
    uriVariables: ['id'=> new Link(
        fromClass: Veterinaire::class,
        fromProperty: 'events',
    
    )],
    operations:
    [
        new GetCollection(
                security:'is_granted("ROLE_VETERINAIRE") or is_granted("ROLE_ADMIN")',
                paginationEnabled:false,
                controller:GetAllEventOfVeterinaireController::class,
                openapiContext:
                [
                    'tags' => ['Veterinaire'],
                    'summary' => 'Get collection of events of the same type',
                    'description' => 'Get all events by type',
                    'response' =>['200' , '401', '403', '404'],
                    'parameters' => [
                        'libType' => [
                            'name' => 'libType',
                            'in' => 'query',
                            'description' => 'The type of the event we want to get  (Urgent, Non Urgent)',
                            'type' => 'string',
                            'required' => false,
                            'openapi' => [
                                'example' => 'Urgent'
                            ]
                        ]
                    ],
        
                ]
                ),
    ]
)]
#[ApiResource(
    uriTemplate: '/clients/{id}/events',
    security:'is_granted("ROLE_CLIENT")',
    openapiContext:[
        'tags' => ['Client']
    ],
    operations:[
        new GetCollection(
            uriTemplate:'/clients/{id}/events',
            controller: GetAllEventOfClientController::class,
            security:'is_granted("ROLE_USER")',
            
)],
           
            ),
]
#[ApiFilter(SearchFilter::class, properties: ['typeEvent.getLibType()' => 'exact'])]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]

    private ?TypeEvent $typeEvent = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
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
