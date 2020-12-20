<?php

namespace App\Twig;

use App\Entity\Avatar;
use App\Entity\User;
use App\Entity\UserRelationship;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('textLimit', [$this, 'textLimit']),
            new TwigFilter('formatAvatarData', [$this, 'formatAvatarData']),
            new TwigFilter('getUserRelationshipMessage', [$this, 'getUserRelationshipMessage']),
        ];
    }

    public function textLimit(string $text, int $charactersNumber): string
    {
        if (strlen($text) >= $charactersNumber) {
            return mb_substr($text, 0, $charactersNumber).' ...';
        } else {
            return $text;
        }
    }

    public function formatAvatarData(Avatar $avatar): string
    {
        return 'https://avataaars.io/?avatarStyle='.$avatar->getAvatarStyle().'&topType='.$avatar->getTopType().'&mouthType='.$avatar->getMouthType().'&facialHairColor='.$avatar->getFacialHairColor().'&facialHairType='.$avatar->getFacialHairType().'&accessoriesType='.$avatar->getAccessoriesType().'&hatColor='.$avatar->getHatColor().'&clotheType='.$avatar->getClotheType().'&eyeType='.$avatar->getEyeType().'&eyebrowType='.$avatar->getEyebrowType().'&clotheColor='.$avatar->getClotheColor().'&graphicType='.$avatar->getGraphicType().'&skinColor='.$avatar->getSkinColor().'&hairColor='.$avatar->getHairColor();
    }

    public function getUserRelationshipMessage(int $status, User $user): string
    {
        $message = '';

        switch ($status) {
            case UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']:
                $message = 'Tu as demandé à suivre '.$user->getUsername().'.';
                break;
            case UserRelationship::STATUS['ACCEPTED_FOLLOW_REQUEST']:
                $message = 'Vous êtes désormais abonné à '.$user->getUsername().'.';
                break;
            case UserRelationship::STATUS['REFUSED_FOLLOW_REQUEST']:
                $message = '';
                break;
        }

        return $message;
    }
}