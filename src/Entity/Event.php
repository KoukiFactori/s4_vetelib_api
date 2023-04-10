<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use APiPlatform\Metadata\Delete;
use APiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use APiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GetAllEventAvailableOfVeterinaireController;
use App\Controller\GetAllEventOfAnimalController;
use App\Controller\GetAllEventOfClientController;
use App\Repository\EventRepository;
use App\Validator\AuthenticatedUserEvent;
use App\Validator\EventBefore;
use App\Validator\EventCanStartAt;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/events',
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Get(
            uriTemplate: '/events/{id}',
            paginationEnabled: false,
            security: 'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user) or is_granted("ROLE_ADMIN")',
            openapi: new Model\Operation(
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        required: true,
                        schema: [
                            'type' => 'integer'
                        ]
                    )
                ]
            )
        ),
        new Post(
            uriTemplate: '/events',
            security: 'is_granted("ROLE_CLIENT") or is_granted("ROLE_ADMIN")',
        ),
        new Patch(
            uriTemplate: '/events/{id}',
            security: 'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user) or is_granted("ROLE_ADMIN")',
            openapi: new Model\Operation(
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        required: true,
                        schema: [
                            'type' => 'integer'
                        ]
                    )
                ]
            )
        ),
        new Delete(
            uriTemplate: '/events/{id}',
            security: 'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user) or is_granted("ROLE_ADMIN")',
            openapi: new Model\Operation(
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        required: true,
                        schema: [
                            'type' => 'integer'
                        ]
                    )
                ]
            )
        ),
        new Put(
            uriTemplate: '/events/{id}',
            security: 'is_granted("ROLE_USER") and (object.getVeterinaire() == user or object.getAnimal().getClient() == user) or is_granted("ROLE_ADMIN")',
            openapi: new Model\Operation(
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        required: true,
                        schema: [
                            'type' => 'integer'
                        ]
                    )
                ]
            )
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['typeEvent.libType' => 'exact'])]
#[ApiFilter(BooleanFilter::class)]
#[ApiResource(
    uriTemplate: '/animals/{id}/events',
    uriVariables: ['id' => new Link(
        fromClass: Animal::class,
        fromProperty: 'events',
    )],
    operations: [
        new GetCollection(
            uriTemplate: '/animals/{id}/events',
            security: 'is_granted("ROLE_USER")',
            controller: GetAllEventOfAnimalController::class,
            openapi: new Model\Operation(
                tags: ['Animal']
            )
        ),
])]
#[ApiResource(
    uriTemplate: '/veterinaires/{id}/events',
    uriVariables: ['id' => new Link(
        fromClass: Veterinaire::class,
        fromProperty: 'events',
    )],
    openapi: new Model\Operation(
        tags: ['Veterinaire']
    ),
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_VETERINAIRE") or is_granted("ROLE_ADMIN")',
            paginationEnabled: false,
        ),
        new GetCollection(
            uriTemplate: '/veterinaires/{id}/events/available/{date}',
            uriVariables: [
                'id' => new Link(
                    fromClass: Veterinaire::class,
                    fromProperty: 'events',
                ),
            ],
            controller: GetAllEventAvailableOfVeterinaireController::class,
            security: 'is_granted("ROLE_USER")',
            requirements: [
                'id' => '\d+',
                'date'=> '.+'
            ],
            openapi: new Model\Operation(
                parameters: [
                    new Model\Parameter(
                       name: 'id',
                       in: 'path',
                       required: true
                    ),
                    new Model\Parameter(
                        name: 'date',
                        in: 'path',
                        required: true
                    )
                ]
            )
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['typeEvent.libType' => 'exact'])]
#[ApiFilter(BooleanFilter::class)]
#[ApiResource(
    uriTemplate: '/clients/{id}/events',
    security: 'is_granted("ROLE_CLIENT")',
    openapi: new Model\Operation(
        tags: ['Client']
    ),
    operations: [
        new GetCollection(
            uriTemplate: '/clients/{id}/events',
            controller: GetAllEventOfClientController::class,
            security: 'is_granted("ROLE_USER")',
        )],
),
]
#[ApiFilter(SearchFilter::class, properties: ['typeEvent.getLibType()' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['date' => DateFilter::EXCLUDE_NULL])]
#[UniqueEntity(['date', 'animal'], message: 'Vous avez déjà un rendez-vous à cette date', )]
#[UniqueEntity(['date', 'veterinaire'], message: 'Le vétérinaire est déjà pris à cette date', )]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[EventBefore]
    #[EventCanStartAt]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[AuthenticatedUserEvent]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeEvent $typeEvent = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Veterinaire $veterinaire = null;

    #[ORM\Column]
    private ?bool $isUrgent = null;

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

    public function isIsUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(bool $isUrgent): self
    {
        $this->isUrgent = $isUrgent;

        return $this;
    }
}
