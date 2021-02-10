<?php

namespace App\Security;

use App\Entity\Opinion;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OpinionVoter extends Voter
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

        if (!$subject instanceof Opinion) {
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

        /** @var Opinion $opinion */
        $opinion = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($opinion, $user);
            case self::DELETE:
                return $this->canDelete($opinion, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Opinion $opinion, User $user): bool
    {
        return $opinion->getAuthor() === $user;
    }

    private function canDelete(Opinion $opinion, User$user): bool
    {
        return $this->canEdit($opinion, $user);
    }
}