<?php
declare(strict_types=1);


namespace App\Services;


use App\Core\Connection;
use App\Models\Entity;
use PDO;

/**
 * Class EntityService
 * Сервис для работы с сущностями
 * @package Services
 */
class EntityService
{

    private string $tableName = 'entities';

    private Connection $connection;
    private PaginationService $paginator;

    public function __construct(PaginationService $paginator)
    {
        $this->connection = Connection::getInstance();
        $this->paginator  = $paginator;
    }


    public function findBy()
    {

    }

    /**
     * Получить сущности для страницы
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function byPage(int $limit = 20, int $page = 1): array
    {

        $this->paginator->setLimit($limit);

        $total = $this->connection->query("select count(*) from {$this->tableName}")
                                    ->fetchColumn();

        $stmt = $this->connection->prepare("select * from {$this->tableName} order by name limit :limit offset :offset;");
        $offset = $this->paginator->calcOffset($page);

        /*$stmt->bindParam(':limit',$limit , PDO::PARAM_INT);
        $stmt->bindParam(':offset',$offset, PDO::PARAM_INT)*/;
        $test = $stmt->execute(['limit'=>$limit,'offset'=>$offset]);
        var_dump($test);
        return [];
    }

    /**
     * Удалить сущность по id
     * @param int $id
     */
    public function delete(int $id)
    {

    }

}