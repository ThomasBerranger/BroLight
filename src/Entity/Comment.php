<?php

namespace App\Entity;

use App\Helper\CreatedAtBasicTrait;
use App\Helper\UpdatedAtBasicTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"author", "tmdbId"}, message="Cet utilisateur a déjà rédigé un commentaire à propos de ce film.")
 */
class Comment
{
    use CreatedAtBasicTrait;
    use UpdatedAtBasicTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"comment:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comment:read"})
     */
    private $author;

    /**
     * @ORM\Column(name="tmdb_id", type="integer")
     * @Groups({"comment:read", "user:read"})
     */
    private $tmdbId;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comment:read", "user:read"})
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity=View::class, mappedBy="comment")
     */
    private $view;

    /**
     * @ORM\Column(type="boolean")
     */
    private $spoiler;

    public function __construct()
    {
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
            $this->view->setComment(null);
        }

        // set the owning side of the relation if necessary
        if ($view !== null && $view->getComment() !== $this) {
            $view->setComment($this);
        }

        $this->view = $view;

        return $this;
    }

    public function getSpoiler(): ?bool
    {
        return $this->spoiler;
    }

    public function setSpoiler(bool $spoiler): self
    {
        $this->spoiler = $spoiler;

        return $this;
    }
}
