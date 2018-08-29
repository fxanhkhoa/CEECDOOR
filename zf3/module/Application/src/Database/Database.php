<?php

namespace Application\Database;
use Zend\DB\Adapter\Adapter;

class Database{

    public function getHistory(){
        $adapter = new \Zend\Db\Adapter\Adapter(array(
            'driver' => 'Pdo_Mysql',
            'database' => 'framework',
            'username' => 'fxanhkhoa',
            'password' => '03021996',
            'hostname' => 'localhost',
            'charset' => 'utf8'
        ));

        $sql = "select * from USERUSAGE";

        $statement = $adapter->query($sql);

        $result = $statement->execute();
        $platform = $adapter->platform;

        return $result;
    }
}

?>