<?php
declare(strict_types=1);

use App\Config;
use App\Core\Connection;
use Faker\Factory;

require_once 'vendor/autoload.php';

Config::init();

$migration = new class
{
    private string $name;
    private Connection $connection;
    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $this->name = $this->connection->getDatabaseName() . '.entities';
    }

    public function up()
    {
        $query = "create table if not exists {$this->name}
                    (
                        id          int auto_increment,
                        name        varchar(50)                         not null,
                        description text                                not null,
                        created     timestamp default (current_timestamp()) not null,
                        constraint entities_id_uindex
                                    unique (id),
                        constraint entities_name_uindex
                                    unique (name)
                    )ENGINE=MyIsam;

                    create fulltext index entities_description
                                on $this->name (description);

                    alter table $this->name
                        add primary key (id);";
        try{
            $statement = $this->connection->prepare($query);
            $statement->execute();
        }catch (PDOException $exception){
            echo $exception->getMessage();
            exit();
        }
    }

    public function down()
    {
        $query = "drop table if exists $this->name;";

        try{
            $statement = $this->connection->prepare($query);
            $statement->execute();

        }catch (PDOException $exception){
            echo $exception->getMessage();
            exit();
        }
    }

    public function seed(int $count): void
    {
        $faker     = Factory::create('ru_RU');
        $query     = 'insert into ' . $this->name . '(name,description,created) values(:name,:description,:created)';
        $statement = $this->connection->prepare($query);

        for($i = 0; $i < $count; ++$i){

            $statement->bindParam('name',$faker->userName);
            $statement->bindParam('description',$faker->realText(300));
            $statement->bindParam('created',date('Y-m-d H:i:s', $faker->unixTime));
            $statement->execute();
        }
    }
};


/**
 * Вызов методов объекта миграции из консоли
 */
$migration->{$argv[1]}(isset($argv[2]) ? intval($argv[2]) : null);