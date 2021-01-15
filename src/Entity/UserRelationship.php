<?php

namespace App\Entity;

use App\Helper\DoctrinePersistLifecycleTrait;
use App\Helper\DoctrineUpdateLifecycleTrait;
use App\Repository\UserRelationshipRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRelationshipRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class UserRelationship
{
    use DoctrinePersistLifecycleTrait;
    use DoctrineUpdateLifecycleTrait;

    const STATUS = [
        'PENDING_FOLLOW_REQUEST' => 1,
        'ACCEPTED_FOLLOW_REQUEST' => 2,
        'REFUSED_FOLLOW_REQUEST' => 3,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRelationsAsTarget")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userTarget;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRelationsAsSource")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userSource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getUserTarget(): ?User
    {
        return $this->userTarget;
    }

    public function setUserTarget(?User $userTarget): self
    {
        $this->userTarget = $userTarget;

        return $this;
    }

    public function getUserSource(): ?User
    {
        return $this->userSource;
    }

    public function setUserSource(?User $userSource): self
    {
        $this->userSource = $userSource;

        return $this;
    }

}
