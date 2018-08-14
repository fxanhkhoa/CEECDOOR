<?php
namespace Users\Service;
use Users\Entity\Users;
use Zend\Crypt\Password\Bcrypt;

class UserManager{

    private $entityManager;
    public function __construct($entityManager){
        $this->entityManager = $entityManager; 
        // echo 'ok usermanager';
    }

    public function checkEmailExists($email){
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['EMAIL'=>$email]);
        // if ($user!==null) return true;
        // return false;
        return $user !== null;
    }

    public function checkUsernameExists($username){
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['USERNAME'=>$username]);
        // if ($user!==null) return true;
        // return false;
        return $user !== null;
    }

    public function addUser($data){
        if ($this->checkEmailExists($data['email'])){
            throw new \Exception("Email " . $data['email'] ." is used");
        }

        if ($this->checkUsernameExists($data['username'])){
            throw new \Exception("Username " . $data['username'] . " is used");
        }

        //`USERNAME`, `PASSWORD`,
        //`FULLNAME`, `BIRTHDAY`, `GENDER`, `ADDRESS`, `EMAIL`, `PHONE`, `ROLE`

        $user = new Users();
        $user->setUSERNAME($data['username']);
        $user->setFULLNAME($data['fullname']);

        $birthday = new \DateTime($data['birthday']);
        $birthday->format('Y-m-d');
        $user->setBIRTHDAY($birthday);
        $user->setGENDER($data['gender']);
        $user->setADDRESS($data['address']);
        $user->setEMAIL($data['email']);
        $user->setPHONE($data['phone']);
        $user->setROLE($data['role']);

        $bcrypt = new Bcrypt();
        $securePass = $bcrypt->create($data['password']);
        $user->setPASSWORD($securePass);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}

?>