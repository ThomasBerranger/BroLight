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

    public function pluralize($value): string
    {
        if ($value and $value > 0 or $value and is_iterable($value)) {
            return 's';
        } else {
            return '';
        }
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