<?php
namespace Users\Service;

use Zend\Authentication\Adapter\AdapterInterface;

class AuthAdapter implements AdapterInterface{
    private $entityManager;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        // echo 'ok usermanager';
    }
}

?>