<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull()]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $participant = null;    

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: AssociationHasEvent::class, orphanRemoval: true)]
    private Collection $associationHasEvents;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: GroupHasEvent::class, orphanRemoval: true)]
    private Collection $groupHasEvents;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventRequest::class, orphanRemoval: true)]
    private Collection $eventRequests;


    public function __construct()
    {
        $this->start = new DateTimeImmutable();
        $this->end = new DateTimeImmutable;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable;
        $this->associationHasEvents = new ArrayCollection();
        $this->groupHasEvents = new ArrayCollection();
        $this->eventRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ?self
    {
        $this->id = $id;

        return $this;
    }
    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getParticipant(): ?int
    {
        return $this->participant;
    }

    public function setParticipant(?int $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
    public function getCreated_at(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreated_at():void
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function getUpdated_at(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setUpdated_at(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, AssociationHasEvent>
     */
    public function getAssociationHasEvents(): Collection
    {
        return $this->associationHasEvents;
    }

    public function addAssociationHasEvent(AssociationHasEvent $associationHasEvent): self
    {
        if (!$this->associationHasEvents->contains($associationHasEvent)) {
            $this->associationHasEvents->add($associationHasEvent);
            $associationHasEvent->setEvent($this);
        }

        return $this;
    }

    public function removeAssociationHasEvent(AssociationHasEvent $associationHasEvent): self
    {
        if ($this->associationHasEvents->removeElement($associationHasEvent)) {
            // set the owning side to null (unless already changed)
            if ($associationHasEvent->getEvent() === $this) {
                $associationHasEvent->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupHasEvent>
     */
    public function getGroupHasEvents(): Collection
    {
        return $this->groupHasEvents;
    }

    public function addGroupHasEvent(GroupHasEvent $groupHasEvent): self
    {
        if (!$this->groupHasEvents->contains($groupHasEvent)) {
            $this->groupHasEvents->add($groupHasEvent);
            $groupHasEvent->setEvent($this);
        }

        return $this;
    }

    public function removeGroupHasEvent(GroupHasEvent $groupHasEvent): self
    {
        if ($this->groupHasEvents->removeElement($groupHasEvent)) {
            // set the owning side to null (unless already changed)
            if ($groupHasEvent->getEvent() === $this) {
                $groupHasEvent->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventRequest>
     */
    public function getEventRequests(): Collection
    {
        return $this->eventRequests;
    }

    public function addEventRequest(EventRequest $eventRequest): self
    {
        if (!$this->eventRequests->contains($eventRequest)) {
            $this->eventRequests->add($eventRequest);
            $eventRequest->setEvent($this);
        }

        return $this;
    }

    public function removeEventRequest(EventRequest $eventRequest): self
    {
        if ($this->eventRequests->removeElement($eventRequest)) {
            // set the owning side to null (unless already changed)
            if ($eventRequest->getEvent() === $this) {
                $eventRequest->setEvent(null);
            }
        }

        return $this;
    }  
}
