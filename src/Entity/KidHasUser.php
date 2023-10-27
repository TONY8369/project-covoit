<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\KidHasUserRepository;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: KidHasUserRepository::class)]
class KidHasUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Kid')]
    #[ORM\JoinColumn(nullable: false ,onDelete:"CASCADE")]
    private ?Kid $kid = null;

    #[ORM\ManyToOne(inversedBy: 'kidHasUsers')]
    #[ORM\JoinColumn(nullable: false ,onDelete: "CASCADE")]
    private ?User $user= null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

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

    public function getKid(): ?Kid
    {
        return $this->kid;
    }

    public function setKid(?Kid $kid): self
    {
        $this->kid = $kid;

        return $this;
    }

    public function getUser(): ?self
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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
