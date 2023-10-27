<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRequestRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EventRequestRepository::class)]
class EventRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'eventRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $nb_seat = null;

    #[ORM\Column(length: 255)]
    private ?string $direction = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departure_time = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'event_request', targetEntity: EventExchange::class, orphanRemoval: true)]
    private Collection $eventExchanges;

    public function __construct()
    {
        $this->departure_time = new DateTimeImmutable;
        $this->eventExchanges = new ArrayCollection();
        $this->created_at = new DateTimeImmutable;
        $this->updated_at = new DateTimeImmutable;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNbSeat(): ?int
    {
        return $this->nb_seat;
    }

    public function setNbSeat(int $nb_seat): self
    {
        $this->nb_seat = $nb_seat;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departure_time;
    }

    public function setDepartureTime(\DateTimeInterface $departure_time): self
    {
        $this->departure_time = $departure_time;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, EventExchange>
     */
    public function getEventExchanges(): Collection
    {
        return $this->eventExchanges;
    }

    public function addEventExchange(EventExchange $eventExchange): self
    {
        if (!$this->eventExchanges->contains($eventExchange)) {
            $this->eventExchanges->add($eventExchange);
            $eventExchange->setEventRequest($this);
        }

        return $this;
    }

    public function removeEventExchange(EventExchange $eventExchange): self
    {
        if ($this->eventExchanges->removeElement($eventExchange)) {
            // set the owning side to null (unless already changed)
            if ($eventExchange->getEventRequest() === $this) {
                $eventExchange->setEventRequest(null);
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
