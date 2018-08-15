<?php
namespace Users\Service;

use Users\Entity\Users;
use Users\Entity\UsersUsage;
use Zend\Crypt\Password\Bcrypt;

class UserManager
{

    private $entityManager;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        // echo 'ok usermanager';
    }

    public function checkEmailExists($email)
    {
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['EMAIL' => $email]);
        // if ($user!==null) return true;
        // return false;
        return $user !== null;
    }

    public function checkUsernameExists($username)
    {
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['USERNAME' => $username]);
        // if ($user!==null) return true;
        // return false;
        return $user !== null;
    }

    public function checkRFIDExists($rfid)
    {
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['RFID' => $rfid]);
        return $user !== null;
    }

    public function addUser($data)
    {
        if ($this->checkEmailExists($data['email'])) {
            throw new \Exception("Email " . $data['email'] . " is used");
        }

        if ($this->checkUsernameExists($data['username'])) {
            throw new \Exception("Username " . $data['username'] . " is used");
        }

        //`USERNAME`, `PASSWORD`,
        //`FULLNAME`, `BIRTHDAY`, `GENDER`, `ADDRESS`, `EMAIL`, `PHONE`, `ROLE`

        $user = new Users();
        $user->setUSERNAME($data['username']);
        $user->setRFID($data['rfid']);
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

    public function editUser($user, $data){

        $sql = "select u from Users\Entity\Users u where u.EMAIL = '" .$data['email']."' and u.USERNAME != '" . $data['username'] . "'";
        $q = $this->entityManager->createQuery($sql);
        $users = $q->getResult();

        if (!empty($users)){
            throw new \Exception("Email " . $data['email'] . " is used");
        }
        // if ($this->checkEmailExists($data['email'])) {
        //     throw new \Exception("Email " . $data['email'] . " is used");
        // }

        $user->setUSERNAME($data['username']);
        $user->setRFID($data['rfid']);
        $user->setFULLNAME($data['fullname']);

        $birthday = new \DateTime($data['birthday']);
        $birthday->format('Y-m-d');
        $user->setBIRTHDAY($birthday);
        $user->setGENDER($data['gender']);
        $user->setADDRESS($data['address']);
        $user->setEMAIL($data['email']);
        $user->setPHONE($data['phone']);
        $user->setROLE($data['role']);

        $this->entityManager->flush();
        return $user;
    }

    public function addUSERUSAGE($data)
    {
        //`ID`, `USERNAME`, `TIME`, `DAY`, `RFID`, `GHICHU`
        if (!$this->checkRFIDExists($data['rfid'])) {
            throw new \Exception("RFID " . $data['rfid'] . " doesn't exist");
        }

        //Get info from users
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['RFID' => $data['rfid']]);

        $userusage = new UsersUsage();

        //Username
        $userusage->setUSERNAME($user->getUSERNAME());

        //Set Time Zone
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //Time
        $time = new \DateTime(date("h:i:sa"));
        $userusage->setTIME($time);

        //Day
        $day = new \DateTime(date('Y-m-d'));
        $userusage->setDAY($day);

        //RFID
        $userusage->setRFID($data['rfid']);

        //GHICHU
        $userusage->setGHICHU('nothing');

        $this->entityManager->persist($userusage);
        $this->entityManager->flush();
        return $userusage;
    }
}
