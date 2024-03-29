<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EspeceRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EspeceRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/especes',
            security: "is_granted('ROLE_USER')",
            openapiContext: [
                'summary' => 'Récupérer la collection des espèces',
                'description' => 'Récupérer la collection des espèces',
            ]
        ),
        new Get(
            uriTemplate: '/especes/{id}',
            paginationEnabled: false,
            security: "is_granted('ROLE_USER')",
            openapiContext: [
                'summary' => 'Get One Species',
                'description' => 'Get one species',
                'responses' => [
                    '200' => [
                        'description' => 'Recovery of the species by its id',
                    ],
                    '401' => [
                        'description' => 'Not authorized, you are not logged in',
                    ],
                    '403' => [
                        'description' => 'Not authorized, you do not have the rights',
                    ],
                    '404' => [
                        'description' => 'The species does not exist',
                    ],
                    '500' => [
                        'description' => 'Server Error',
                    ],
                ],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'The id of the species',
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
class Espece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('animal:read:collection')]
    private ?string $name = null;

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
}
