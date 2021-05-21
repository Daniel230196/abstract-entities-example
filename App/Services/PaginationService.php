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


    public function getForPage($table, $page, $perPage = 20)
    {

    }

    /**
     * Расчет офсета для запроса на основании номера страницы
     * @param int $page
     * @return float|int
     */
    public function calcOffset(int $page)
    {
        $offset = ($page - 1) * $this->limit;
        return $offset === 0 ? 1 : $offset;
    }

    /**
     * Общее кол-во страниц, на основании кол-ва строк в таблице и текущего лимита
     */
    public function countPages(int $total)
    {
        return ceil($total / $this->limit);
    }
}