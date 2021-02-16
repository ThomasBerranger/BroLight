<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(fields={"email"}, message="Quelqu'un utilise déjà cet email.")
 */
class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private ?string $lastname;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\Length(min=2, max=255)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private string $slug;

    /**
     * @ORM\OneToOne(targetEntity=Avatar::class, mappedBy="author", cascade={"remove"})
     */
    private ?Avatar $avatar;

    /**
     * @ORM\OneToOne(targetEntity=Podium::class, mappedBy="author", cascade={"persist", "remove"})
     */
    private ?Podium $podium;

    /**
     * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="author", orphanRemoval=true)
     */
    private $opinions;

    /**
     * @ORM\OneToMany(targetEntity=Relationship::class, mappedBy="userSource", orphanRemoval=true)
     * @OrderBy({"updatedAt" = "ASC"})
     */
    private $relationsAsSource;

    /**
     * @ORM\OneToMany(targetEntity=Relationship::class, mappedBy="userTarget", orphanRemoval=true)
     * @OrderBy({"updatedAt" = "ASC"})
     */
    private $relationsAsTarget;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Wish::class, mappedBy="author", orphanRemoval=true)
     */
    private $wishes;

    public function __construct()
    {
        $this->userRelationsAsSource = new ArrayCollection();
        $this->userRelationsAsTarget = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->wishes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFirstname().' '.$this->getLastname();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles))
            $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(Avatar $avatar): self
    {
        $this->avatar = $avatar;

        if ($avatar->getAuthor() !== $this) {
            $avatar->setAuthor($this);
        }

        return $this;
    }
    public function getPodium(): ?Podium
    {
        return $this->podium;
    }

    public function setPodium(?Podium $podium): self
    {
        // unset the owning side of the relation if necessary
        if ($podium === null && $this->podium !== null) {
            $this->podium->setAuthor(null);
        }

        // set the owning side of the relation if necessary
        if ($podium !== null && $podium->getAuthor() !== $this) {
            $podium->setAuthor($this);
        }

        $this->podium = $podium;

        return $this;
    }

    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setAuthor($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            if ($opinion->getAuthor() === $this) {
                $opinion->setAuthor(null);
            }
        }

        return $this;
    }

    public function getOpinionsTmdbIds(): array
    {
        $opinionsTmdbIds = [];

        foreach ($this->opinions as $opinion) {
            array_push($opinionsTmdbIds, $opinion->getTmdbId());
        }

        return $opinionsTmdbIds;
    }

    public function getPendingFollowings(): array
    {
        $pendingFollowings = [];

        foreach ($this->relationsAsSource as $userRelation) {
            if ($userRelation->getStatus() === Relationship::STATUS['PENDING_FOLLOW_REQUEST']) {
                array_push($pendingFollowings, $userRelation->getUserTarget());
            }
        }

        return $pendingFollowings;
    }

    public function getFollowings(): array
    {
        $followings = [];

        foreach ($this->relationsAsSource as $userRelation) {
            if ($userRelation->getStatus() === Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST']) {
                array_push($followings, $userRelation->getUserTarget());
            }
        }

        return $followings;
    }

    public function getPendingFollowers(): array
    {
        $pendingFollowers = [];

        /** @var Relationship $userRelation */
        foreach ($this->relationsAsTarget as $userRelation) {
            if ($userRelation->getStatus() === Relationship::STATUS['PENDING_FOLLOW_REQUEST']) {
                array_push($pendingFollowers, $userRelation->getUserSource());
            }
        }

        return $pendingFollowers;
    }

    public function getFollowers(): array
    {
        $followers = [];

        foreach ($this->relationsAsTarget as $userRelation) {
            if ($userRelation->getStatus() === Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST']) {
                array_push($followers, $userRelation->getUserSource());
            }
        }

        return $followers;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection|Wish[]
     */
    public function getWishes(): Collection
    {
        return $this->wishes;
    }

    public function addWish(Wish $wish): self
    {
        if (!$this->wishes->contains($wish)) {
            $this->wishes[] = $wish;
            $wish->setAuthor($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): self
    {
        if ($this->wishes->removeElement($wish)) {
            if ($wish->getAuthor() === $this) {
                $wish->setAuthor(null);
            }
        }

        return $this;
    }

    public function getWishTmdbIds(): array
    {
        $wishTmdbIds = [];

        foreach ($this->wishes as $wish) {
            array_push($wishTmdbIds, $wish->getTmdbId());
        }

        return $wishTmdbIds;
    }
}
