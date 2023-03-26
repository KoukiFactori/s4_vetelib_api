<?php

namespace App\Entity;

use ApiPlatform\OpenApi\Model;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\GetMeController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap(['client' => Client::class, 'veterinaire' => Veterinaire::class, 'admin' => Admin::class])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:get']]
)]
#[GetCollection(
    security: 'is_granted("ROLE_ADMIN")',
    openapi: new Model\Operation(
        summary: 'Retrieves all users',
        description: 'Retrieves all the users in the database',
        responses: [
            '200' => [
                'description' => 'List of users',
            ],
            '401' => [
                'description' => 'Unauthorized'
            ],
            '403' => [
                'description' => "You don't have permission to interact with this route",
            ],
        ],
    )
)]
#[GetCollection(
    controller: GetMeController::class,
    paginationEnabled: false,
    security: 'is_granted("ROLE_USER")',
    uriTemplate: '/me',
    openapi: new Model\Operation(
        summary: 'Retrieves the connected user',
        description: 'Retrieves the current connected user, returns an error if user is not connected',
        responses: [
            '200' => [
                'description' => 'Current user returns by the security layout',
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ],
            '401' => [
                'description' => 'Unauthorized',
            ],
            '403' => [
                'description' => "You don't have access to this ressource"
            ]
        ],
    )
)]
#[Patch(
    security: 'is_granted("ROLE_USER") and object = user',
    denormalizationContext: ['groups' => ['user:set']],
    openapi: new Model\Operation(
        summary: 'Patch an User',
        description: 'Allow user to patch current informations by providing updated said informations',
        responses: [
            "500" => "Server Error, try later",
            "403" => "You don't permission to interact with this entity",
            "401" => "Unauthorized.",
            "201" => "Updated"
        ]
    )
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:get')]
    protected ?int $id = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 180, unique: true)]
    protected ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups('user:set')]
    protected ?string $password = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 60, nullable: true)]
    private ?string $city = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $zipcode = null;

    #[Groups(['user:get', 'user:set'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
