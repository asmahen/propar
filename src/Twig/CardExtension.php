<?php

namespace App\Twig;

use App\Entity\Operations;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CardExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
//            new TwigFilter('image', [$this, 'imageChoice']),

        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('image', [$this, 'imageChoice']),
            new TwigFunction('color', [$this, 'colorCard']),
        ];
    }

    public function imageChoice(Operations $operation)
    {

        if ($operation->getCategories() == 'Petite') {
            return 'https://www.nettoyagecanape.fr/images/nettoyage/entreprise-nettoyage-paris.jpg';
        } elseif ($operation->getCategories() == 'Moyenne') {
            return 'https://www.entreprisenettoyage.net/public/upload/img/desinfection-locaux.jpg';
        } else {
            return 'https://objectifpropreteparis.com/wp-content/uploads/2012/07/nettoyage-de-vitres-2.jpg';
        }
    }

    public function colorCard(Operations $operation)
    {
        if ($operation->getCategories() == "Petite") {
            return 'info';
        } elseif ($operation->getCategories() == 'Moyenne') {
            return 'warning';
        } else {
            return 'secondary';
        }
    }
}
