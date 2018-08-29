<?php

namespace Database\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class SqlController extends AbstractActionController{

    public function AdapterDB(){
        $adapter = new Adapter([
            'driver'=>'Pdo_Mysql',
            'database' => 'framework',
            'username' => 'fxanhkhoa',
            'password' => '03021996',
            'hostname' => 'localhost',
            'charset' => 'utf8'
        ]);

        return $adapter;
    }

    public function selectAction(){
        $adapter = $this->AdapterDB();

        $sql = new Sql($adapter);

        $select = $sql->select();
        $select->from('USERUSAGE');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        foreach ($result as $data){
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
        
        return false;
    }
}