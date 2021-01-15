<?php

namespace App\Helper;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\HasLifecycleCallbacks()
 */
trait DoctrinePersistLifecycleTrait
{
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"comment:read", "user:read"})
     */
    private $createdAt;

    /**
     * @ORM\PrePersist()
     */
    public function setCreateDefaultValues()
    {
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }
}