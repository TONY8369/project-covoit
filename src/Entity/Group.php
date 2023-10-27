<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[ORM\HasLifecycleCallbacks]
class Group
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $headcount = null;

    #[ORM\ManyToOne(inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Association $association = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private $created_at;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;    

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: GroupHasKid::class, orphanRemoval: true)]
    private Collection $kid;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: GroupHasEvent::class, orphanRemoval: true)]
    private Collection $groupHasEvents;

    public function __construct()
    {
        $this->kid_id = new ArrayCollection();
        $this->groupHasEvents = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHeadcount(): ?int
    {
        return $this->headcount;
    }


    public function setHeadcount(int $headcount): self
    {
        $this->headcount = $headcount;

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

    public function getUpdated_at(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setUpdated_at(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }    

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    /**
     * @return Collection<int, GroupHasKid>
     */
    public function getKid(): Collection
    {
        return $this->kid;
    }

    public function addKid(GroupHasKid $kid): self
    {
        if (!$this->kid->contains($kid)) {
            $this->kid->add($kid);
            $kid->setGroup($this);
        }

        return $this;
    }

    public function removeKid(GroupHasKid $kid): self
    {
        if ($this->kid->removeElement($kid)) {
            // set the owning side to null (unless already changed)
            if ($kid->getGroup() === $this) {
                $kid->setGroup(null);
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
            $groupHasEvent->setGroupId($this);
        }

        return $this;
    }

    public function removeGroupHasEvent(GroupHasEvent $groupHasEvent): self
    {
        if ($this->groupHasEvents->removeElement($groupHasEvent)) {
            // set the owning side to null (unless already changed)
            if ($groupHasEvent->getGroupId() === $this) {
                $groupHasEvent->setGroupId(null);
            }
        }

        return $this;
    }
}
