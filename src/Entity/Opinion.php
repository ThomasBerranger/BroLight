<?php

namespace App\Entity;

use App\Helper\CreatedAtBasicTrait;
use App\Helper\UpdatedAtBasicTrait;
use App\Repository\OpinionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OpinionRepository::class)
 * @UniqueEntity(fields={"author", "tmdbId"}, message="Cet utilisateur a déjà un avis à propos de ce film.")
 * @ORM\HasLifecycleCallbacks()
 */
class Opinion
{
    use CreatedAtBasicTrait;
    use UpdatedAtBasicTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"opinion:read"})
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="opinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"opinion:read"})
     */
    private ?int $tmdbId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"opinion:read"})
     */
    private ?bool $isViewed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"opinion:read"})
     */
    private ?\DateTimeInterface $viewedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"opinion:read"})
     */
    private ?string $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"opinion:read"})
     */
    private ?\DateTimeInterface $commentedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"opinion:read"})
     */
    private ?bool $isSpoiler;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"opinion:read"})
     */
    private ?int $rate;

    private ?array $movie;

    public function __construct()
    {
        $this->isViewed = true;
        $this->viewedAt = null;
        $this->comment = null;
        $this->commentedAt = null;
        $this->isSpoiler = false;
        $this->rate = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
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

    public function getIsViewed(): ?bool
    {
        return $this->isViewed;
    }

    public function setIsViewed(?bool $isViewed): self
    {
        $this->isViewed = $isViewed;

        return $this;
    }

    public function getViewedAt(): ?\DateTimeInterface
    {
        return $this->viewedAt;
    }

    public function setViewedAt(?\DateTimeInterface $viewedAt): self
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCommentedAt(): ?\DateTimeInterface
    {
        return $this->commentedAt;
    }

    public function setCommentedAt(?\DateTimeInterface $commentedAt): self
    {
        $this->commentedAt = $commentedAt;

        return $this;
    }

    public function getIsSpoiler(): ?bool
    {
        return $this->isSpoiler;
    }

    public function setIsSpoiler(?bool $isSpoiler): self
    {
        $this->isSpoiler = $isSpoiler;

        return $this;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getMovie(): ?array
    {
        return $this->movie;
    }

    public function setMovie(?array $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
