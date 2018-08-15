<?php

namespace Users\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="USERUSAGE")
 */
class UsersUsage
{
    //`ID`, `USERNAME`, `TIME`, `DAY`, `RFID`, `GHICHU`
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $ID;

    /** @ORM\Column(type="string") */
    private $USERNAME;

    /** @ORM\Column(type="time") */
    private $TIME;

    /** @ORM\Column(type="date") */
    private $DAY;

    /** @ORM\Column(type="string") */
    private $RFID;

    /** @ORM\Column(type="string") */
    private $GHICHU;

    /**
     * @return
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param
     */
    public function setID($ID)
    {
        $this->ID = $ID;
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
    public function getTIME()
    {
        return $this->TIME;
    }

    /**
     * @param
     */
    public function setTIME($TIME)
    {
        $this->TIME = $TIME;
    }

    /**
     * @return
     */
    public function getDAY()
    {
        return $this->DAY;
    }

    /**
     * @param
     */
    public function setDAY($DAY)
    {
        $this->DAY = $DAY;
    }

    /**
     * @return
     */
    public function getRFID()
    {
        return $this->RFID;
    }

    /**
     * @param
     */
    public function setRFID($RFID)
    {
        $this->RFID = $RFID;
    }

    /**
     * @return
     */
    public function getGHICHU()
    {
        return $this->GHICHU;
    }

    /**
     * @param
     */
    public function setGHICHU($GHICHU)
    {
        $this->GHICHU = $GHICHU;
    }
}

?>