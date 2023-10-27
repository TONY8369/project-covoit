<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupHasEventRepository;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: GroupHasEventRepository::class)]
class GroupHasEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groupHasEvents')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Group $group = null;

    #[ORM\ManyToOne(inversedBy: 'groupHasEvents')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Event $event = null;

    #[ORM\Column]
    private ?int $nb_participant = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroupId(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getNbParticipant(): ?int
    {
        return $this->nb_participant;
    }

    public function setNbParticipant(int $nb_participant): self
    {
        $this->nb_participant = $nb_participant;

        return $this;
    }

    public function getCreated_at(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreated_at(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getUpdated_at(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setUpdated_at(DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
