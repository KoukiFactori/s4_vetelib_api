<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
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
           
        ),
        new Get(
            uriTemplate: '/typeEvents/{id}',
            security: "is_granted('ROLE_USER')",
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
            uriTemplate: '/typeEvents',
            security: "is_granted('ROLE_ADMIN')",
           
        ),
        new Patch(
            uriTemplate: '/typeEvents/{id}',
            security: "is_granted('ROLE_ADMIN')",
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
            uriTemplate: '/typeEvents/{id}',
            security: "is_granted('ROLE_ADMIN')",
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
            uriTemplate: '/typeEvents/{id}',
            security: "is_granted('ROLE_ADMIN')",
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
