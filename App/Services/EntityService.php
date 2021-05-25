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

    /**
     * @var string
     */
    private string $tableName;

    private Connection $connection;
    private PaginationService $paginator;

    public function __construct(PaginationService $paginator)
    {
        $this->tableName = Entity::getTableName();
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


    /**
     * Поиск позиций по тексту
     * @param array $text
     * @return array
     */
    public function findByText(array $text): array
    {
        $stmt = $this->connection->prepare("select * from {$this->tableName} where description like ?");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = [];

        foreach ($text as $item){
            $stmt->execute(['%'.$item.'%']);
            $res = array_merge( $res, $this->createEntitiesStmt($stmt->fetchAll()));
        }

        return $res;
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

    /**
     * Создать массив сущностей
     * @param array $data
     * @return array
     */
    private function createEntitiesStmt(array $data): array
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = EntityFactory::fromStatement($item);
        }
        return $result;
    }

}