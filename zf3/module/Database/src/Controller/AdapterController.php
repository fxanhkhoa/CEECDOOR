<?php

namespace Database\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;

class AdapterController extends AbstractActionController{

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

    public function indexAction(){
        
        $database = $this->AdapterDB();

        $sql = "select * from USERUSAGE LIMIT 0,4";
        $statement = $database->query($sql);

        $result = $statement->execute();

        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';

        foreach ($result as $row){
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }

        return false;
    }
}
