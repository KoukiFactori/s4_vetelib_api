<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnimalRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Validator\AuthenticatedUserAnimal;
use Doctrine\Common\Collections\Collection;
use App\Controller\GetUserAnimalsController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\GetAllAnimalOfVeterinaireController;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['animal:read', 'animal:owner:read', 'animal:espece:read']
    ],
    operations: [
        new GetCollection(
            uriTemplate: '/animals',
            security: 'is_granted("ROLE_ADMIN")',
            openapi: new Model\Operation(
                summary: 'Get all animals',
                description: 'Get all animals',
                operationId: 'fetchAllAnimals',
                responses: [
                    '200' => new Model\Response(
                        description: 'A list of animals',
                    ),
                    '401' => new Model\Response(
                        description: 'Not authorized, you are not logged in',
                    ),
                    '403' => new Model\Response(
                        description: 'Not authorized, you do not have the rights',
                    ),
                    '404' => new Model\Response(
                        description: 'The animal does not exist',
                    )
                ]
            )
        ),
        new Get(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VETERINAIRE") or (is_granted("ROLE_CLIENT") and object.getClient() == user)',   // Un client ne peut voir que ses animaux
            openapi: new Model\Operation(
                summary: 'Get One Animal',
                description: 'Get one Animal',
                operationId: 'fetchOneAnimal',
                responses: [
                    '200' => new Model\Response(
                        description: 'Returns an objet representing an Animal',
                    ),
                    '401' => new Model\Response(
                        description: 'Not authorized, you are not logged in',
                    ),
                    '403' => new Model\Response(
                        description: 'Not authorized, you do not have the rights',
                    ),
                    '404' => new Model\Response(
                        description: 'The animal does not exist',
                    )
                ],
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'The id of the animal',
                        required: true,
                        examples: new \ArrayObject([1])
                    )
                ],
            )
        ),
        new Post(
            uriTemplate: '/animals',
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_CLIENT")',   // Un client ne peut pas créer d'animal pour un autre client
            openapi: new Model\Operation(
                summary: 'Create an animal',
                description: 'Create an animal',
                responses: [
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
            )
        ),
        new Patch(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_CLIENT") and object.getClient() == user)',   // Un client ne peut pas modifier un animal qui ne lui appartient pas
            openapi: new Model\Operation(
                summary: 'Update an animal',
                description: 'Update an animal',
                responses: [
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
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'The id of the animal',
                        required: true
                    )
                ]
            )
        ),
        new Put(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            openapi: new Model\Operation(
                summary: 'Update an animal',
                description: 'Update an animal',
                responses: [
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
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'The id of the animal',
                        required: true
                    )
                ]
            )
        ),
        new Delete(
            uriTemplate: '/animals/{id}',
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_CLIENT") and object.getClient() == user)',   // Un client ne peut pas supprimer un animal qui ne lui appartient pas
            openapi: new Model\Operation(
                summary: 'Delete an animal',
                description: 'Delete an animal',
                responses: [
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
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'The id of the animal',
                        required: true
                    )
                ]
            )
        )
    ]
)]
#[ApiResource(
    uriTemplate: '/veterinaires/{id}/animals',
    requirements: [
        'id' => '\d+'
    ],
    normalizationContext: [
        'groups' => ['animal:read', 'animal:owner:read', 'animal:espece:read']
    ],
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VETERINAIRE")',
            controller: GetAllAnimalOfVeterinaireController::class,
            openapi: new Model\Operation(
                tags: ['Veterinaire'],
                summary: 'Get all animals of a veterinaire',
                description: 'Get all animals of a veterinaire',
                responses: [
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
                parameters: [
                    new Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'The id of the veterinarian',
                        required: true,
                        example: 1
                    )
                ],
            )
        ),
    ]
)]
#[ApiResource(
    uriTemplate: '/clients/{id}/animals',
    uriVariables: ['id' => new Link(
        fromClass: Client::class,
        fromProperty: 'animals',
    )],
    requirements: [
        'id' => '\d+'
    ],
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_CLIENT") and id == user.getId()) or is_granted("ROLE_VETERINAIRE")',
            paginationEnabled: false,
            openapi: new Model\Operation(
                summary: 'Retrieves all the animals for a given client',
                description: 'Retrieves all the animals for a given client',
                tags: ['Client'],
                parameters: [
                    new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        description: 'The id of the animal',
                        required: true
                    )
                ]
            )
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
    #[Groups('animal:read')]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups('animal:read')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('animal:read')]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('animal:espece:read')]
    private ?Espece $espece = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    #[AuthenticatedUserAnimal]
    #[Groups('animal:owner:read')]
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
