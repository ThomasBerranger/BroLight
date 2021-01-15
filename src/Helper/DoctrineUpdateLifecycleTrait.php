<?php

namespace App\Helper;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\HasLifecycleCallbacks()
 */
trait DoctrineUpdateLifecycleTrait
{
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"comment:read", "user:read"})
     */
    private $updatedAt;

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdateDefaultValues()
    {
        $this->updatedAt = new \DateTime();
    }
}