<?php

namespace App\Twig;

use App\Entity\Avatar;
use App\Entity\User;
use App\Entity\UserRelationship;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('textLimit', [$this, 'textLimit']),
            new TwigFilter('formatAvatarData', [$this, 'formatAvatarData']),
            new TwigFilter('historyDateFormat', [$this, 'historyDateFormat']),
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

    public function historyDateFormat(DateTime $date): string
    {
        $now = new DateTime();
        $interval = $now->getTimestamp() - $date->getTimestamp();

        if ($interval <= 60) {
            return 'Il y a '.$interval.' seconde'.($interval > 1?'s':'');
        } elseif ($interval <= 60 * 60) {
            return 'Il y a '.intdiv($interval, 60).' minute'.(intdiv($interval, 60) > 1 ? 's' : '');
        } elseif ($interval <= 24 * 60 * 60) {
            return 'Il y a '.intdiv($interval, 60 * 60).' heure'.(intdiv($interval, 60 * 60) > 1 ? 's' : '');
        } else  {
            return $date->format('Y-m-d');
        }
    }
}