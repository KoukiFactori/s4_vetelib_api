<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\EspeceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspeceRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
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
        new Post(
            uriTemplate: '/especes',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Create a Species',
                'description' => 'Create a species',
                'responses' => [
                    '201' => [
                        'description' => 'Species created',
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
            ]
        ),
        new Patch(
            uriTemplate: 'especes/{id}',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Update a Species',
                'description' => 'Update a species',
                'responses' => [
                    '200' => [
                        'description' => 'Species updated',
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
            ]
        ),
        new Put(
            uriTemplate: 'especes/{id}',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Update a Species',
                'description' => 'Update a species',
                'responses' => [
                    '200' => [
                        'description' => 'Species updated',
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
            ]
        ),
        new Delete(
            uriTemplate: 'especes/{id}',
            security: "is_granted('ROLE_ADMIN')",
            openapiContext: [
                'summary' => 'Delete a Species',
                'description' => 'Delete a species',
                'responses' => [
                    '204' => [
                        'description' => 'Species deleted',
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
            ]
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
