<?php

namespace App\Security;

use App\Entity\Avatar;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
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

        if (!$subject instanceof User) {
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

        /** @var User $userSubject*/
        $userSubject = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($userSubject, $user);
            case self::DELETE:
                return $this->canDelete($userSubject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(User $userSubject, User $user): bool
    {
        return $userSubject === $user;
    }

    private function canDelete(User $userSubject, User $user): bool
    {
        return $this->canEdit($userSubject, $user);
    }
}