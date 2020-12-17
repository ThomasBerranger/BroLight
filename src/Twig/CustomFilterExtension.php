<?php

namespace App\Twig;

use App\Entity\Avatar;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('textLimit', [$this, 'textLimit']),
            new TwigFilter('formatAvatarData', [$this, 'formatAvatarData']),
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
}