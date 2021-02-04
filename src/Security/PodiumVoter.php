<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Podium;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PodiumVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Podium) {
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

        /** @var Podium $podium */
        $podium = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($podium, $user);
            case self::DELETE:
                return $this->canDelete($podium, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Podium $podium, User $user): bool
    {
        return $podium->getAuthor() === $user;
    }

    private function canDelete(Podium $podium, User$user): bool
    {
        return $this->canEdit($podium, $user);
    }
}