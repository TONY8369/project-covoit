<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventExchangeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EventExchangeRepository::class)]
class EventExchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventExchanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventRequest $event_request = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'eventExchanges')]
    #[ORM\JoinColumn(nullable: false ,onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'eventExchanges')]
    #[ORM\JoinColumn(nullable: false , onDelete: "CASCADE")]
    private ?Kid $kid = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;    

    #[ORM\OneToMany(mappedBy: 'event_exchange', targetEntity: EventExchangeTimeline::class, orphanRemoval: true)]
    private Collection $eventExchangeTimelines;

    public function __construct()
    {
        $this->eventExchangeTimelines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventRequest(): ?EventRequest
    {
        return $this->event_request;
    }

    public function setEventRequest(?EventRequest $event_request): self
    {
        $this->event_request = $event_request;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getKid(): ?Kid
    {
        return $this->kid;
    }

    public function setKid(?Kid $kid): self
    {
        $this->kid = $kid;

        return $this;
    }

    /**
     * @return Collection<int, EventExchangeTimeline>
     */
    public function getEventExchangeTimelines(): Collection
    {
        return $this->eventExchangeTimelines;
    }

    public function addEventExchangeTimeline(EventExchangeTimeline $eventExchangeTimeline): self
    {
        if (!$this->eventExchangeTimelines->contains($eventExchangeTimeline)) {
            $this->eventExchangeTimelines->add($eventExchangeTimeline);
            $eventExchangeTimeline->setEventExchange($this);
        }

        return $this;
    }

    public function removeEventExchangeTimeline(EventExchangeTimeline $eventExchangeTimeline): self
    {
        if ($this->eventExchangeTimelines->removeElement($eventExchangeTimeline)) {
            // set the owning side to null (unless already changed)
            if ($eventExchangeTimeline->getEventExchange() === $this) {
                $eventExchangeTimeline->setEventExchange(null);
            }
        }

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
    public function setUpdated_at(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
