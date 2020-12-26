<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\View;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ViewVoter extends Voter
{
    const EDIT = 'edit';
    const REMOVE = 'remove';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::REMOVE])) {
            return false;
        }

        if (!$subject instanceof View) {
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

        /** @var View $view */
        $view = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($view, $user);
            case self::REMOVE:
                return $this->canRemove($view, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(View $view, User $user): bool
    {
        return $view->getAuthor() === $user;
    }

    private function canRemove(View $view, User$user): bool
    {
        return $this->canEdit($view, $user);
    }
}