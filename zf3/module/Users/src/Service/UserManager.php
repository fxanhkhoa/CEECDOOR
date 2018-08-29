<?php
namespace Users\Service;

use Users\Entity\Users;
use Users\Entity\UsersUsage;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;


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

    public function removeUser($user){
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function verifyPassword($securePass, $password){
        $bcrypt = new Bcrypt();

        if ($bcrypt->verify($password, $securePass)) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword($user, $data){
        $securePass = $user->getPassword();
        $password = $data['old_pw'];
        if (!$this->verifyPassword($securePass, $password)){
            return false;
        }

        $newPassword = $data['new_pw'];

        $bcrypt = new Bcrypt();
        $securePass = $bcrypt->create($newPassword);
        $user->setPASSWORD($securePass);

        $this->entityManager->flush();
        return true;
    }

    public function createTokenPasswordReset($user){
        $token = Rand::getString(32, '0123456789qwertyuiopasdfghjklzxcvbnm', true);
        $user->setPasswordResetToken($token);

        $dateCreate = date('Y-m-d H:i:s');
        $dateCreate = new \DateTime($dateCreate);
        $dateCreate->format('Y-m-d H:i:s');
        $user->setPasswordResetTokenDate($dateCreate);
        $this->entityManager->flush();

        $http = isset($_SERVER['HTTPS']) ? "https://" : "http://";
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "localhost";
        // echo $host;
        $url = $http.$host."/CEECDOOR/zf3/public/set-password/".$token;

        $bodyMessage = "Hello ". $user->getFullname(). "
                        Please click this link below to reset password: 
                        $url 
                        if you don't need to, please discard this message
                        ";

        $message = new Message();
        $message->addTo($user->getEMAIL());
        $message->addFrom('sgu.ddt1141@gmail.com');
        $message->setSubject('ResetPassword!');
        $message->setBody($bodyMessage);

        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options   = new SmtpOptions([
            'name'              => 'smtp.gmail.com',
            'host'              => 'smtp.gmail.com',
            'port'              => 587,
            'connection_class'  => 'login',
            'connection_config' => [
                'username' => 'sgu.ddt1141@gmail.com',
                'password' => 'ddt1141.sgu',
                'port'     => 587,
                'ssl'      => 'tls',
            ],
        ]);
        $transport->setOptions($options);
        $transport->send($message);
    }

    public function checkResetPasswordToken($token){
        $user = $this->entityManager->getRepository(Users::class)
            ->findOneBy(['pw_reset_token'=>$token]);
        if (!$user){
            return false;
        }

        $userTokenDate = $user->getPasswordResetTokenDate()->getTimestamp();
        $now = new \Datetime('now');
        $now = $now->getTimestamp();
        if ($now - $userTokenDate > 3600){
            return false;
        }
        return true;
    }

    public function setNewPasswordByToken($token, $newPassword){
       if (!$this->checkResetPasswordToken($token)){
           return false;
       }
       
       $user = $this->entityManager->getRepository(Users::class)
            ->findOneBy(['pw_reset_token'=>$token]);

       if (!$user){
           return false;
       }
       else{
           $bcrypt = new Bcrypt();
           $passwordHash = $bcrypt->create($newPassword);
           $user->setPASSWORD($passwordHash);

           //reset
           $user->setPasswordResetTokenDate(null);
           $user->setPasswordResetToken(null);
           $this->entityManager->flush();
           return true;
       }
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
