<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\KidRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KidRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Kid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $anniversary = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;


    #[ORM\OneToMany(mappedBy: 'kid', targetEntity: KidHasUser::class, cascade: ["remove"])]
    private Collection $kidHasUsers;

    #[ORM\OneToMany(mappedBy: 'kid', targetEntity: GroupHasKid::class, cascade: ["remove"] )]
    private Collection $groupHasKid;

    #[ORM\OneToMany(mappedBy: 'kid', targetEntity: EventExchange::class )]
    private Collection $eventExchanges;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private $created_at;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private $updated_at;

    public function __construct()
    {
        $this->anniversary = new DateTimeImmutable;
        $this->kidHasUsers = new ArrayCollection();
        $this->groupHasKid = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
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

    public function getanniversary(): ?\DateTimeInterface
    {
        return $this->anniversary;
    }

    public function setanniversary(\DateTimeInterface $anniversary): self
    {
        $this->anniversary = $anniversary;

        return $this;
    }

    public function getcomment(): ?string
    {
        return $this->comment;
    }

    public function setcomment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, KidHasUser>
     */
    public function getKidHasUsers(): Collection
    {
        return $this->kidHasUsers;
    }

    public function addKidHasUser(KidHasUser $kidHasUser): ?self
    {
        if (!$this->kidHasUsers->contains($kidHasUser)) {
            $this->kidHasUsers->add($kidHasUser);
            $kidHasUser->setKid($this);
        }

        return $this;
    }

    public function removeKidHasUser(KidHasUser $kidHasUser): self
    {
        if ($this->kidHasUsers->removeElement($kidHasUser)) {
            // set the owning side to null (unless already changed)
            if ($kidHasUser->getKid() === $this) {
                $kidHasUser->setKid(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupHasKid>
     */
    public function getGroupHasKid(): Collection
    {
        return $this->groupHasKid;
    }

    public function addGroupHasKid(GroupHasKid $groupHasKid): self
    {
        if (!$this->groupHasKid->contains($groupHasKid)) {
            $this->groupHasKid->add($groupHasKid);
            $groupHasKid->setKid($this);
        }

        return $this;
    }

    public function removeGroupHasKid(GroupHasKid $groupHasKid): self
    {
        if ($this->groupHasKid->removeElement($groupHasKid)) {
            // set the owning side to null (unless already changed)
            if ($groupHasKid->getKid() === $this) {
                $groupHasKid->setKid(null);
            }
        }

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
            $eventExchange->setKid($this);
        }

        return $this;
    }

    public function removeEventExchange(EventExchange $eventExchange): self
    {
        if ($this->eventExchanges->removeElement($eventExchange)) {
            // set the owning side to null (unless already changed)
            if ($eventExchange->getKid() === $this) {
                $eventExchange->setKid(null);
            }
        }

        return $this;
    }

    public function getCreated_at(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreated_at(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getUpdated_at(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setUpdated_at(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }


}
