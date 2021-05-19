<?php
declare(strict_types=1);

use App\Config;
use App\Core\Connection;

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
                        created     timestamp default CURRENT_TIMESTAMP not null,
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

    public function seed(int $count)
    {

    }
};

$migration->{$argv[1]}();