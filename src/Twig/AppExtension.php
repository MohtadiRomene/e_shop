<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Extension Twig pour des fonctions personnalisées
 */
class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    /**
     * Formater un prix (convertit string en float si nécessaire)
     */
    public function formatPrice($price): string
    {
        $floatPrice = is_string($price) ? (float) $price : $price;
        return number_format($floatPrice, 2, ',', ' ');
    }
}
