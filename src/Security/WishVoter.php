<?php

namespace App\Security;

use App\Entity\Opinion;
use App\Entity\User;
use App\Entity\Wish;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WishVoter extends Voter
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

        if (!$subject instanceof Wish) {
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

        /** @var Wish $wish */
        $wish = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($wish, $user);
            case self::DELETE:
                return $this->canDelete($wish, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Wish $wish, User $user): bool
    {
        return $wish->getAuthor() === $user;
    }

    private function canDelete(Wish $wish, User$user): bool
    {
        return $this->canEdit($wish, $user);
    }
}