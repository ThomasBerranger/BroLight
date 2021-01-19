<?php

namespace App\Twig;

use App\Entity\Avatar;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Asset\Packages;

class CustomFilterExtension extends AbstractExtension
{
    private $assetManager;

    public function __construct(Packages $assetManager)
    {
        $this->assetManager = $assetManager;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('textLimit', [$this, 'textLimit']),
            new TwigFilter('formatAvatarData', [$this, 'formatAvatarData']),
            new TwigFilter('historyDateFormat', [$this, 'historyDateFormat']),
            new TwigFilter('associatedEmojiURL', [$this, 'associatedEmojiURL']),
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
        } elseif ($interval <= 24 * 60 * 60 * 2) {
            return 'Hier';
        } else {
            return $date->format('d/m/Y');
        }
    }

    public function associatedEmojiURL(int $rate): string
    {
        $url = "";

        switch ($rate) {
            case 1:
                $url = $this->assetManager->getUrl('images/emojis/dizzy.svg');
                break;
            case 2:
                $url = $this->assetManager->getUrl('images/emojis/sad.svg');
                break;
            case 3:
                $url = $this->assetManager->getUrl('images/emojis/happy.svg');
                break;
            case 4:
                $url = $this->assetManager->getUrl('images/emojis/3d-glasses.svg');
                break;
            case 5:
                $url = $this->assetManager->getUrl('images/emojis/love.svg');
                break;
        }

        return $url;
    }
}