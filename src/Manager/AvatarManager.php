<?php

namespace App\Manager;

use App\Entity\Avatar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AvatarManager
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Security $security;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function save(Avatar $avatar): Avatar
    {
        $errors = $this->validator->validate($avatar);

        if ($this->security->isGranted('edit', $avatar) and count($errors) <= 0) {
            $this->entityManager->persist($avatar);
            $this->entityManager->flush();
        }

        return $avatar;
    }
}