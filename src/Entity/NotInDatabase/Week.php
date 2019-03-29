<?php

namespace App\Entity\NotInDatabase;
use App\Entity\Astreinte;

class Week
{
    private $date;
    private $astreinte;


    public function __construct(\DateTime $date, Astreinte $astreinte = null)
    {
        $this->date = $date;
        $this->astreinte = $astreinte;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function getAstreinte()
    {
        return $this->astreinte;
    }

    public function getDimanche()
    {
        return (new \DateTime())->setISODate($this->date("Y"), $this->date("W"), 7);
    }

    public function getClass(): ?string
    {
        if($this->date < new \DateTime()){
            return "passed";
        }else if($this->astreinte == null){
            return "notset";
        }

        return "set";
    }

    public function getMondayDate(): ?\DateTime
    {        
        $date = $this->date;
        if ($date->format('N') == 1) {
            return $date;
        } else {
            return $date->modify('last monday');
        }
    }
    public function getSundayDate(): ?\DateTime
    {        
        $date = $this->date;
        if ($date->format('N') == 7) {
            return $date;
        } else {
            return $date->modify('next Sunday');
        }
    }

    public function getWeekNum(): ?string
    {
        return $this->date->format("W");
    }
}