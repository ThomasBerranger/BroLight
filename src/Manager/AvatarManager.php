<?php

namespace App\Manager;

use App\Entity\Avatar;
use App\Entity\User;
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

    public function createAvatarFor(User $user): Avatar // todo: repasser avec le user doctrine event listener
    {
        $avatar = new Avatar();
        $avatar->setAuthor($user);

        $this->entityManager->persist($avatar);
        $this->entityManager->flush();

        return $avatar;
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