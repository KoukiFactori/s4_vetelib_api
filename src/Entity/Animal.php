<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model;
use App\Controller\GetAllAnimalOfVeterinaireController;
use App\Controller\GetUserAnimalsController;
use App\Repository\AnimalRepository;
use App\Validator\AuthenticatedUserAnimal;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/animals',
            security: 'is_granted("ROLE_ADMIN")',
            openapiContext: [
                'summary' => 'Get all animals',
                'description' => 'Get all animals',
            ],
        ),
        new Get(
            uriTemplate: '/animals/{id}',
            paginationEnabled: false,
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VETERINAIRE") or (is_granted("ROLE_CLIENT") and object.getClient() == user)',   // Un client ne peut voir que ses animaux
            openapiContext: [
                'summary' => 'Get One Animal',
                'description' => 'Get one Animal',
                'responses' => [
                    '200' => [
                        'description' => 'Recovery of the animal by its id',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The animal does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the animal',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
            ],
        ),
        new Post(
            uriTemplate: '/animals',
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_CLIENT")',   // Un client ne peut pas créer d'animal pour un autre client
            openapiContext: [
                'summary' => 'Create an animal',
                'description' => 'Create an animal',
                'responses' => [
                    '201' => [
                        'description' => 'Animal created',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The animal does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
            ],
        ),
        new Patch(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_CLIENT") and object.getClient() == user)',   // Un client ne peut pas modifier un animal qui ne lui appartient pas
            openapiContext: [
                'summary' => 'Update an animal',
                'description' => 'Update an animal',
                'responses' => [
                    '200' => [
                        'description' => 'Animal updated',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The animal does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the animal',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
            ],
        ),
        new Put(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            openapiContext: [
                'summary' => 'Update an animal',
                'description' => 'Update an animal',
                'responses' => [
                    '200' => [
                        'description' => 'Animal updated',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The animal does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the animal',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
            ],
        ),
        new Delete(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_CLIENT") and object.getClient() == user)',   // Un client ne peut pas supprimer un animal qui ne lui appartient pas
            openapiContext: [
                'summary' => 'Delete an animal',
                'description' => 'Delete an animal',
                'responses' => [
                    '204' => [
                        'description' => 'Animal deleted',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The animal does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the animal',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
            ],
        ),
    ]
)]
#[ApiResource(
    uriTemplate: '/veterinaires/{id}/animals',
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VETERINAIRE")',
            paginationEnabled: false,
            controller: GetAllAnimalOfVeterinaireController::class,
            openapiContext: [
                'tags' => ['Veterinaire'],
                'summary' => 'Get all animals of a veterinaire',
                'description' => 'Get all animals of a veterinaire',
                'responses' => [
                    '200' => [
                        'description' => 'Animals of a veterinaire',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The veterinaire does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                    ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the veterinarian',
                        'required' => true,
                        'type' => 'integer',
                        'openapi' => [
                            'example' => 1,
                        ],
                    ],
                ],
            ]
        ),
    ]
)]
#[ApiResource(
    uriTemplate: '/clients/{id}/animals',
    uriVariables: ['id' => new Link(
        fromClass: Client::class,
        fromProperty: 'animals',
    )],
    openapiContext: [
        'tags' => ['Client'],
    ],
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_CLIENT") and id == user.getId())',
            paginationEnabled: false,
        ),
    ]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/me/animals',
            security: 'is_granted("ROLE_USER")',
            controller: GetUserAnimalsController::class,
            itemUriTemplate: '/animals/{id}',
            openapi: new Model\Operation(
                tags: ['Client'],
                summary: 'Get all animals from the current user',
                description: 'Allow the current connected user to get all the animals they have',
            )
        ),
    ]
)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Espece $espece = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    #[AuthenticatedUserAnimal]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getEspece(): ?Espece
    {
        return $this->espece;
    }

    public function setEspece(?Espece $espece): self
    {
        $this->espece = $espece;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
            $event->setAnimal($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAnimal() === $this) {
                $event->setAnimal(null);
            }
        }

        return $this;
    }
}
