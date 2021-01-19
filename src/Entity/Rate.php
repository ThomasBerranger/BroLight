<?php

namespace App\Entity;

use App\Helper\DoctrinePersistLifecycleTrait;
use App\Helper\DoctrineUpdateLifecycleTrait;
use App\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RateRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"author", "tmdbId"}, message="Cet utilisateur a déjà noté ce film.")
 */
class Rate
{
    use DoctrinePersistLifecycleTrait;
    use DoctrineUpdateLifecycleTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmdbId;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\OneToOne(targetEntity=View::class, mappedBy="rate", cascade={"remove"})
     */
    private $view;

    public function __construct()
    {
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getView(): ?View
    {
        return $this->view;
    }

    public function setView(?View $view): self
    {
        // unset the owning side of the relation if necessary
        if ($view === null && $this->view !== null) {
            $this->view->setRate(null);
        }

        // set the owning side of the relation if necessary
        if ($view !== null && $view->getRate() !== $this) {
            $view->setRate($this);
        }

        $this->view = $view;

        return $this;
    }
}
