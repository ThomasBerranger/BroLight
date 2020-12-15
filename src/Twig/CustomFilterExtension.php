<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('pluralize', [$this, 'pluralize']),
            new TwigFilter('textLimit', [$this, 'textLimit']),
        ];
    }

    public function textLimit($text, $charactersNumber): string
    {
        if (strlen($text) >= $charactersNumber) {
            return mb_substr($text, 0, $charactersNumber).' ...';
        } else {
            return $text;
        }
    }
}