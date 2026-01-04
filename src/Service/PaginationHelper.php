<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Helper pour gérer la pagination simple
 */
class PaginationHelper
{
    /**
     * Calculer la pagination
     */
    public function paginate(array $items, Request $request, int $itemsPerPage = 12): array
    {
        $page = max(1, (int) $request->query->getInt('page', 1));
        $totalItems = count($items);
        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));
        $page = min($page, $totalPages);
        
        $offset = ($page - 1) * $itemsPerPage;
        $paginatedItems = array_slice($items, $offset, $itemsPerPage);
        
        return [
            'items' => $paginatedItems,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'has_previous' => $page > 1,
            'has_next' => $page < $totalPages,
            'previous_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $totalPages ? $page + 1 : null,
        ];
    }
}
