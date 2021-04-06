<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Md5Extension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('md5', [$this, 'calculateMd5']),
        ];
    }

    public function calculateMd5(string $value): string
    {
        return md5($value);
    }
}