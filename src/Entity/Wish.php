<?php

namespace App\Entity;

use App\Helper\CreatedAtBasicTrait;
use App\Helper\UpdatedAtBasicTrait;
use App\Repository\WishRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WishRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Wish
{
    use CreatedAtBasicTrait;
    use UpdatedAtBasicTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wishes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $tmdbId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): self
    {
        $this->tmdbId = $tmdbId;

        return $this;
    }
}
