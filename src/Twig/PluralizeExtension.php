<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PluralizeExtension extends AbstractExtension
{

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
