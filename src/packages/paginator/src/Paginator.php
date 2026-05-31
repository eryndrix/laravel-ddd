<?php declare(strict_types=1);

namespace Eryndrix\Paginator;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * @template TItem
 * @extends \Illuminate\Pagination\LengthAwarePaginator<int, TItem>
 */
final class Paginator extends LengthAwarePaginator
{
   /**
     * @param array<int, TItem> $items
     * @param int $perPage
     * @param Request|null $request
     */
    public function __construct(
        array $items,
        int $perPage = 10,
        ?Request $request = null
    ) {
        $request ??= request();
        $currentPage = max(1,
            (int) $request->query(key: 'page', default: '1')
        );
        
        parent::__construct(
            items: $this->fromArray(
                items: $items,
                perPage: $perPage,
                currentPage: $currentPage
            ),
            total: count(value: $items),
            perPage: $perPage,
            currentPage: $currentPage,
            options: [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
    }

    /**
     * @param array<int, TItem> $items
     * @param int $perPage
     * @param int $currentPage
     * 
     * @return array<int, TItem>
     */
    private function fromArray(
        array $items,
        int $perPage,
        int $currentPage): array
    {
        return array_slice(
            array: $items,
            offset: ($currentPage - 1) * $perPage,
            length: $perPage
        );
    }
}
