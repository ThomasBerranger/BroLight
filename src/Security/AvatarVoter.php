<?php

namespace App\Security;

use App\Entity\Avatar;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AvatarVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Avatar) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Avatar $avatar */
        $avatar = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($avatar, $user);
            case self::DELETE:
                return $this->canDelete($avatar, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Avatar $avatar, User $user): bool
    {
        return $avatar->getAuthor() === $user;
    }

    private function canDelete(Avatar $avatar, User$user): bool
    {
        return $this->canEdit($avatar, $user);
    }
}