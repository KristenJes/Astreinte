<?php

namespace App\Entity\NotInDatabase;

class Weeks
{
    private $astreintes;
    private $year;
    private $indexAstreinte;


    public function __construct(array $astreintes, int $year)
    {
        $this->astreintes = $astreintes;
        $this->year = $year;
        
        if($this->astreintes != null && count($this->astreintes) != 0){
            $this->indexAstreinte = 0;
        }else{
            $this->indexAstreinte = -1;
        }
    }

    public function getAstreintes(): ?array
    {
        return $this->astreintes;
    }

    public function getIndexAstreinte(): ?\integer
    {
        return $this->indexAstreinte;
    }

    public function getNextAstreinte()
    {
        if($this->indexAstreinte == -1)
            return null;
        return $this->astreintes[$this->indexAstreinte];
    }

    public function getYear(): ?\integer
    {
        return $this->year;
    }


    public function defineNextAstreinte()
    {
        if($this->indexAstreinte != count($this->astreintes)-1){
            $this->indexAstreinte += 1;
        }else{
            $this->indexAstreinte = -1;
        }
    }

    public function getByMonth()
    {
        $weeks = array();
        $date = new \DateTime('first day of January ' . $this->year);
        
        while ($date->format("Y") == $this->year) {
            $astreinte = null;
            // Recherche si la prochaine Astreinte est égale a la semaine actuelle
            if($this->getNextAstreinte() != null && $date->format("W") == $this->getNextAstreinte()->getSemaine()){
                $astreinte = $this->getNextAstreinte();
                $this->defineNextAstreinte();
            }

            $weeks[$this->frDate($date)][] = new Week(new \DateTime($date->format("Y-m-d")), $astreinte);
            $date->modify("+7 days");
        }

        return $weeks;
    }

    public function frDate($date)
    {
        return strftime("%B", $date->getTimestamp());
    }
}