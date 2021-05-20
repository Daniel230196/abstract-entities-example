<?php
declare(strict_types=1);


namespace App\Services;

/**
 * Class PaginationService
 * Класс для постраничной пагинации сущностей
 * @package Services
 */
class PaginationService
{
    private int $limit;

    public function __construct()
    {
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function countRows(): int
    {

    }

    public function getForPage($table, $page,$perPage = 20)
    {

    }

    public function calcOffset(int $page)
    {
        return ($page - 1) * $this->limit;
    }

    public function countPages(int $total)
    {
        return ceil($total / $this->limit);
    }
}