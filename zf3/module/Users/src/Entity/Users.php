<?php
namespace Users\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Users
{
    //`USERNAME`, `PASSWORD`,
    //`FULLNAME`, `BIRTHDAY`, `GENDER`, `ADDRESS`, `EMAIL`, `PHONE`, `ROLE`

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /** @ORM\Column(type="string") */
    private $USERNAME;
    /** @ORM\Column(type="string") */
    private $PASSWORD;
    /** @ORM\Column(type="string") */
    private $FULLNAME;
    /** @ORM\Column(type="date") */
    private $BIRTHDAY;
    /** @ORM\Column(type="string") */
    private $GENDER;
    /** @ORM\Column(type="string") */
    private $ADDRESS;
    /** @ORM\Column(type="string", name="EMAIL", unique=TRUE) */
    private $EMAIL;
    /** @ORM\Column(type="string", name="phone") */
    private $PHONE;
    /** @ORM\Column(type="string") */
    private $ROLE;

    //`USERNAME`, `PASSWORD`,
    //`FULLNAME`, `BIRTHDAY`, `GENDER`, `ADDRESS`, `EMAIL`, `PHONE`

    /**
     * @return
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return
     */
    public function getUSERNAME()
    {
        return $this->USERNAME;
    }

    /**
     * @param
     */
    public function setUSERNAME($USERNAME)
    {
        $this->USERNAME = $USERNAME;
    }

    /**
     * @return
     */
    public function getPASSWORD()
    {
        return $this->PASSWORD;
    }

    /**
     * @param
     */
    public function setPASSWORD($PASSWORD)
    {
        $this->PASSWORD = $PASSWORD;
    }

    /**
     * @return
     */
    public function getFULLNAME()
    {
        return $this->FULLNAME;
    }

    /**
     * @param
     */
    public function setFULLNAME($FULLNAME)
    {
        $this->FULLNAME = $FULLNAME;
    }

    /**
     * @return
     */
    public function getBIRTHDAY()
    {
        return $this->BIRTHDAY;
    }

    /**
     * @param
     */
    public function setBIRTHDAY($BIRTHDAY)
    {
        $this->BIRTHDAY = $BIRTHDAY;
    }

    /**
     * @return
     */
    public function getGENDER()
    {
        return $this->GENDER;
    }

    /**
     * @param
     */
    public function setGENDER($GENDER)
    {
        $this->GENDER = $GENDER;
    }

    /**
     * @return
     */
    public function getADDRESS()
    {
        return $this->ADDRESS;
    }

    /**
     * @param
     */
    public function setADDRESS($ADDRESS)
    {
        $this->ADDRESS = $ADDRESS;
    }

    /**
     * @return
     */
    public function getEMAIL()
    {
        return $this->EMAIL;
    }

    /**
     * @param
     */
    public function setEMAIL($EMAIL)
    {
        $this->EMAIL = $EMAIL;
    }

    /**
     * @return
     */
    public function getPHONE()
    {
        return $this->PHONE;
    }

    /**
     * @param
     */
    public function setPHONE($PHONE)
    {
        $this->PHONE = $PHONE;
    }

    /**
     * @return
     */
    public function getROLE()
    {
        return $this->ROLE;
    }

    /**
     * @param
     */
    public function setROLE($ROLE)
    {
        $this->ROLE = $ROLE;
    }
}
