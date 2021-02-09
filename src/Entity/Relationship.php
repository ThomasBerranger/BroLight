<?php

namespace App\Entity;

use App\Helper\CreatedAtBasicTrait;
use App\Helper\UpdatedAtBasicTrait;
use App\Repository\UserRelationshipRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRelationshipRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"userSource", "userTarget"}, message="Ces utilisateurs sont déjà en relation.")
 */
class Relationship
{
    use CreatedAtBasicTrait;
    use UpdatedAtBasicTrait;

    const STATUS = [
        'PENDING_FOLLOW_REQUEST' => 1,
        'ACCEPTED_FOLLOW_REQUEST' => 2
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRelationsAsSource")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userSource;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRelationsAsTarget")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userTarget;

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

    public function getUserSource(): ?User
    {
        return $this->userSource;
    }

    public function setUserSource(?User $userSource): self
    {
        $this->userSource = $userSource;

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
}
