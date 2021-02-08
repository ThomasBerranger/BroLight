<?php

namespace App\Helper;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtBasicTrait
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreateDefaultValues()
    {
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }
}