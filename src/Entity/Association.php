<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AssociationRepository::class)]

class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[Assert\Regex(
    //     pattern: '/\d/',
    //     match: false,
    //     message: 'Your name cannot contain a number'
    // )]
    // #[Assert\Regex(
    //     pattern: '/^$|\s/g',
    //     match: false,
    //     message: 'Your name cannot be empty'
    // )]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Group::class, orphanRemoval: true)]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: AssociationHasEvent::class, orphanRemoval: true)]
    private Collection $associationHasEvents;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: UserHasAssociation::class, orphanRemoval: true)]
    private Collection $userHasAssociations;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->groups = new ArrayCollection();
        $this->associationHasEvents = new ArrayCollection();
        $this->userHasAssociations = new ArrayCollection();
        $this->created_at = new DateTimeImmutable('Europe/Paris');
        $this->updated_at = new DateTimeImmutable;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreated_at(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    //https://symfony.com/doc/current/doctrine/events.html#doctrine-lifecycle-callbacks
    #[ORM\PrePersist]
    public function setCreatedAt(): void
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
    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setAssociation($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getAssociation() === $this) {
                $group->setAssociation(null);
            }
        }

        return $this;
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
            $associationHasEvent->setAssociation($this);
        }

        return $this;
    }

    public function removeAssociationHasEvent(AssociationHasEvent $associationHasEvent): self
    {
        if ($this->associationHasEvents->removeElement($associationHasEvent)) {
            // set the owning side to null (unless already changed)
            if ($associationHasEvent->getAssociation() === $this) {
                $associationHasEvent->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserHasAssociation>
     */
    public function getUserHasAssociations(): Collection
    {
        return $this->userHasAssociations;
    }

    public function addUserHasAssociation(UserHasAssociation $userHasAssociation): self
    {
        if (!$this->userHasAssociations->contains($userHasAssociation)) {
            $this->userHasAssociations->add($userHasAssociation);
            $userHasAssociation->setAssociation($this);
        }

        return $this;
    }

    public function removeUserHasAssociation(UserHasAssociation $userHasAssociation): self
    {
        if ($this->userHasAssociations->removeElement($userHasAssociation)) {
            // set the owning side to null (unless already changed)
            if ($userHasAssociation->getAssociation() === $this) {
                $userHasAssociation->setAssociation(null);
            }
        }

        return $this;
    }

}
