<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PluralizeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'pluralize']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }

    public function pluralize(int $count, string $singular, ?string $plural = null): string
    {
        $plural ??= sprintf('%ss', $singular);

        switch($count) {
            case 1:
                return sprintf("One %s", $singular);
            case 0:
                return sprintf("Zero %s", $plural);
            default:
                return sprintf("%d %s", $count, $plural);
        }
    }
}
