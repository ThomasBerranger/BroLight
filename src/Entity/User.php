<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="Un compte est déjà enregistré avec cet email.")
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=View::class, mappedBy="author")
     */
    private $views;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="author")
     */
    private $rates;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=UserRelationship::class, mappedBy="userSource", orphanRemoval=true)
     */
    private $userRelationsAsSource;

    /**
     * @ORM\OneToMany(targetEntity=UserRelationship::class, mappedBy="userTarget", orphanRemoval=true)
     */
    private $userRelationsAsTarget;

    /**
     * @ORM\OneToOne(targetEntity=Avatar::class, mappedBy="author", cascade={"persist", "remove"})
     */
    private $avatar;

    public function __construct()
    {
        $this->views = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->userRelationsAsSource = new ArrayCollection();
        $this->userRelationsAsTarget = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getUsername();
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

    /**
     * @return Collection|View[]
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(View $view): self
    {
        if (!$this->views->contains($view)) {
            $this->views[] = $view;
            $view->setAuthor($this);
        }

        return $this;
    }

    public function removeView(View $view): self
    {
        if ($this->views->removeElement($view)) {
            // set the owning side to null (unless already changed)
            if ($view->getAuthor() === $this) {
                $view->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getWatchedMoviesTmdbIds(): array
    {
        $watchedMoviesTmdbIds = [];

        foreach ($this->views as $view) {
            array_push($watchedMoviesTmdbIds, $view->getTmdbId());
        }

        return $watchedMoviesTmdbIds;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setAuthor($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getAuthor() === $this) {
                $rate->setAuthor(null);
            }
        }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->firstname.' '.$this->lastname;
    }

    /**
     * @return array
     */
    public function getFollowers(): array
    {
        $followers = [];

        /** @var UserRelationship $userRelation */
        foreach ($this->userRelationsAsTarget as $userRelation) {
            if ($userRelation->getStatus() === UserRelationship::STATUS['ACCEPTED_FOLLOW_REQUEST']) {
                array_push($followers, $userRelation->getUserSource());
            }
        }

        return $followers;
    }

    /**
     * @return array
     */
    public function getPendingFollowers(): array
    {
        $followers = [];

        /** @var UserRelationship $userRelation */
        foreach ($this->userRelationsAsTarget as $userRelation) {
            if ($userRelation->getStatus() === UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']) {
                array_push($followers, $userRelation->getUserSource());
            }
        }

        return $followers;
    }

    /**
     * @return array
     */
    public function getFollowings(): array
    {
        $followings = [];

        /** @var UserRelationship $userRelation */
        foreach ($this->userRelationsAsSource as $userRelation) {
            if ($userRelation->getStatus() === UserRelationship::STATUS['ACCEPTED_FOLLOW_REQUEST']) {
                array_push($followings, $userRelation->getUserTarget());
            }
        }

        return $followings;
    }

    /**
     * @return array
     */
    public function getPendingFollowings(): array
    {
        $followings = [];

        /** @var UserRelationship $userRelation */
        foreach ($this->userRelationsAsSource as $userRelation) {
            if ($userRelation->getStatus() === UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']) {
                array_push($followings, $userRelation->getUserTarget());
            }
        }

        return $followings;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(Avatar $avatar): self
    {
        $this->avatar = $avatar;

        // set the owning side of the relation if necessary
        if ($avatar->getAuthor() !== $this) {
            $avatar->setAuthor($this);
        }

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreateDefaultValues()
    {
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
        $this->roles = [User::ROLE_USER];
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdateDefaultValues()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials()
    {
    }
}
