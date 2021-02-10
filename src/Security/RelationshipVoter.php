<?php

namespace App\Security;

use App\Entity\Relationship;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RelationshipVoter extends Voter
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

        if (!$subject instanceof Relationship) {
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

        /** @var Relationship $relationship */
        $relationship = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($relationship, $user);
            case self::DELETE:
                return $this->canDelete($relationship, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Relationship $relationship, User $user): bool
    {
        return $relationship->getUserSource() === $user or $relationship->getUserTarget() === $user;
    }

    private function canDelete(Relationship $relationship, User$user): bool
    {
        return $this->canEdit($relationship, $user);
    }
}