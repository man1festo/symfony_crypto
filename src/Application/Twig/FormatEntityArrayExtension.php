<?php

namespace App\Application\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatEntityArrayExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('formatEntityArray', [$this, 'formatEntityArray']),
        ];
    }
    public function formatEntityArray( $entityArray): string
    {
        $result = [];
        if (is_array($entityArray)) {
            foreach ($entityArray as $entity) {
                $result[] = $entity['id'];
            }
        }
        return count($result) ? implode(',', $result) : '';
    }
}
