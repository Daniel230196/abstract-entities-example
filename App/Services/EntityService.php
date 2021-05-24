<?php
declare(strict_types=1);


namespace App\Services;


use App\Core\Connection;
use App\Models\Entity;
use App\Models\EntityFactory;
use PDO;

/**
 * Class EntityService
 * Сервис для работы с сущностями
 * @package Services
 */
class EntityService implements ServiceInterface
{

    private string $tableName = 'entities';

    private Connection $connection;
    private PaginationService $paginator;

    public function __construct(PaginationService $paginator)
    {
        $this->connection = Connection::getInstance();
        $this->paginator  = $paginator;
    }

    /**
     * Создать сущность
     * @param array $data
     */
    public function create(array $data)
    {
        $entity = EntityFactory::fromArray($data);
        $stmt   = $this->connection->prepare("insert into {$this->tableName}(name,description,created) values(:name,:description,:created)");
        $stmt->bindParam(':name', $entity->name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $entity->description, PDO::PARAM_STR);
        $stmt->bindParam(':created', date('Y-m-d H:i:s', time()), PDO::PARAM_STR);
        $stmt->execute();
    }


    public function findBy(string $text): array
    {
        $stmt = $this->connection->prepare("select * from {$this->tableName} where match (title, body) against(:text)");



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

        $offset = $this->paginator->calcOffset($page);
        $total  = $this->connection->query("select count(*) from {$this->tableName}")
                                    ->fetchColumn();
        $stmt   = $this->connection->prepare("select * from {$this->tableName} order by id desc limit :limit offset :offset;");


        $stmt->bindParam(':limit',$limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset',$offset, PDO::PARAM_INT);

        $stmt->execute();

        $entitiesArrayData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($entitiesArrayData as &$entityArrayData){
            $entityArrayData['id'] = intval($entityArrayData['id']);
            $entityArrayData       = EntityFactory::fromArray($entityArrayData);

        }

        $entitiesArrayData['countPages'] = $this->paginator->countPages((int)$total);
        $entitiesArrayData['pageLimit']  = $limit;


        return $entitiesArrayData;
    }

    /**
     * Удалить сущность по id
     * @param int $id
     */
    public function delete(int $id)
    {
        try{
            $query = "delete from {$this->tableName} where id=:id";
            $stmt  = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }catch (\PDOException $e){
            echo $e->getMessage();
        }
    }

}