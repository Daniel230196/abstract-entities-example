<?php
declare(strict_types=1);


namespace App\Services;

/**
 * Class PaginationService
 * Класс для постраничной пагинации сущностей
 * @package Services
 */
class PaginationService implements ServiceInterface
{
    private int $limit;

    public function __construct()
    {
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }


    /**
     * Расчет офсета для запроса на основании номера страницы
     * @param int $page
     * @return float|int
     */
    public function calcOffset(int $page)
    {
        return ($page - 1) * $this->limit;
    }

    /**
     * Общее кол-во страниц, на основании кол-ва строк в таблице и текущего лимита
     * @param int $total
     * @return false|float
     */
    public function countPages(int $total)
    {
        return ceil($total / $this->limit);
    }
}