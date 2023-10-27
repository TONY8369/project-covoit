<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity('email')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?int $civility = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank( message: 'This field must be completed')]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'This field must be completed')]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 2, max: 180)]
    #[Assert\Email(
        message: 'The email {{ value }} should be like XXX@XXX.com.',
    )]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;
    
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column]
    private array $roles = [];

    //https://symfony.com/doc/6.3/doctrine/events.html#doctrine-lifecycle-callbacks
    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: KidHasUser::class, orphanRemoval: true)]
    private Collection $kidHasUsers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EventRequest::class, orphanRemoval: true)]
    private Collection $eventRequests;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserHasAssociation::class, orphanRemoval: true)]
    private Collection $userHasAssociations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EventExchange::class)]
    private Collection $eventExchanges;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EventExchangeTimeline::class, orphanRemoval: true)]
    private Collection $eventExchangeTimelines;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable('Europe/Paris');
        $this->updated_at = new DateTimeImmutable;
        $this->kidHasUsers = new ArrayCollection();
        $this->eventRequests = new ArrayCollection();
        $this->userHasAssociations = new ArrayCollection();
        $this->eventExchanges = new ArrayCollection();
        $this->eventExchangeTimelines = new ArrayCollection();
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

    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility(int $civility): self
    {
        $this->civility = $civility;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
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
        //$this->updated_at = $updated_at;
        //return $this;
        $this->updated_at = new DateTimeImmutable();

    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
            $kidHasUser->setUser($this);
        }

        return $this;
    }

    public function removeKidHasUser(KidHasUser $kidHasUser): self
    {
        if ($this->kidHasUsers->removeElement($kidHasUser)) {
            // set the owning side to null (unless already changed)
            if ($kidHasUser->getUser() === $this) {
                $kidHasUser->setUser(null);
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
            $eventRequest->setUser($this);
        }

        return $this;
    }

    public function removeEventRequest(EventRequest $eventRequest): self
    {
        if ($this->eventRequests->removeElement($eventRequest)) {
            // set the owning side to null (unless already changed)
            if ($eventRequest->getUser() === $this) {
                $eventRequest->setUser(null);
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
            $userHasAssociation->setUser($this);
        }

        return $this;
    }

    public function removeUserHasAssociation(UserHasAssociation $userHasAssociation): self
    {
        if ($this->userHasAssociations->removeElement($userHasAssociation)) {
            // set the owning side to null (unless already changed)
            if ($userHasAssociation->getUser() === $this) {
                $userHasAssociation->setUser(null);
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
            $eventExchange->setUser($this);
        }

        return $this;
    }

    public function removeEventExchange(EventExchange $eventExchange): self
    {
        if ($this->eventExchanges->removeElement($eventExchange)) {
            // set the owning side to null (unless already changed)
            if ($eventExchange->getUser() === $this) {
                $eventExchange->setUser(null);
            }
        }

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
            $eventExchangeTimeline->setUser($this);
        }

        return $this;
    }

    public function removeEventExchangeTimeline(EventExchangeTimeline $eventExchangeTimeline): self
    {
        if ($this->eventExchangeTimelines->removeElement($eventExchangeTimeline)) {
            // set the owning side to null (unless already changed)
            if ($eventExchangeTimeline->getUser() === $this) {
                $eventExchangeTimeline->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

        /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
