<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventExchangeTimelineRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EventExchangeTimelineRepository::class)]
class EventExchangeTimeline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'eventExchangeTimelines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventExchange $event_exchange = null;

    #[ORM\ManyToOne(inversedBy: 'eventExchangeTimelines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreated_at(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreated_at(): void
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

    public function getEventExchange(): ?EventExchange
    {
        return $this->event_exchange;
    }

    public function setEventExchange(?EventExchange $event_exchange): self
    {
        $this->event_exchange = $event_exchange;

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

    
}
