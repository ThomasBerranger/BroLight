<?php

namespace App\Entity;

use App\Helper\CreatedAtBasicTrait;
use App\Helper\UpdatedAtBasicTrait;
use App\Repository\ViewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ViewRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"author", "tmdbId"}, message="Cet utilisateur a déjà vu ce film.")
 */
class View
{
    use CreatedAtBasicTrait;
    use UpdatedAtBasicTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"view:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="views")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"view:read"})
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"view:read", "user:read"})
     */
    private $tmdbId;

    private $movie;

    /**
     * @ORM\OneToOne(targetEntity=Comment::class, inversedBy="view")
     */
    private $comment;

    /**
     * @ORM\OneToOne(targetEntity=Rate::class, inversedBy="view", cascade={"remove"})
     */
    private $rate;

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

    public function getMovie(): ?array
    {
        return $this->movie;
    }

    public function setMovie(array $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRate(): ?Rate
    {
        return $this->rate;
    }

    public function setRate(?Rate $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
