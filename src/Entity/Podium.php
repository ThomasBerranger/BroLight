<?php

namespace App\Entity;

use App\Helper\CreatedAtBasicTrait;
use App\Helper\UpdatedAtBasicTrait;
use App\Repository\PodiumRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PodiumRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Podium
{
    use CreatedAtBasicTrait;
    use UpdatedAtBasicTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"podium:read", "user:read"})
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="podium", cascade={"persist", "remove"})
     * @Groups({"podium:read"})
     */
    private ?User $author;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"podium:read", "user:read"})
     */
    private ?int $firstTmdbId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"podium:read", "user:read"})
     */
    private ?int $secondTmdbId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"podium:read", "user:read"})
     */
    private ?int $thirdTmdbId;

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

    public function getFirstTmdbId(): ?int
    {
        return $this->firstTmdbId;
    }

    public function setFirstTmdbId(?int $firstTmdbId): self
    {
        $this->firstTmdbId = $firstTmdbId;

        return $this;
    }

    public function getSecondTmdbId(): ?int
    {
        return $this->secondTmdbId;
    }

    public function setSecondTmdbId(?int $secondTmdbId): self
    {
        $this->secondTmdbId = $secondTmdbId;

        return $this;
    }

    public function getThirdTmdbId(): ?int
    {
        return $this->thirdTmdbId;
    }

    public function setThirdTmdbId(?int $thirdTmdbId): self
    {
        $this->thirdTmdbId = $thirdTmdbId;

        return $this;
    }

    public function assignedTmdbIdToRank(int $rank, int $tmdbId)
    {
        switch ($rank) {
            case 1:
                $this->setFirstTmdbId($tmdbId);
                break;
            case 2:
                $this->setSecondTmdbId($tmdbId);
                break;
            case 3:
                $this->setThirdTmdbId($tmdbId);
                break;
        }
    }
}
