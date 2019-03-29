<?php

namespace App\Entity\NotInDatabase;

class Weeks
{
    private $weeks;
    private $astreintes;
    private $year;
    private $indexAstreinte;


    public function __construct(array $astreintes, int $year)
    {
        $this->astreintes = $astreintes;
        $this->year = $year;
        $this->weeks = array();
        
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

    public function getWeeks(): ?array
    {
        return $this->weeks;
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

        $interval = new \DateInterval('P7D');
        // $period   = new \DatePeriod(new \DateTime("first sunday of January " . $this->year), $interval, new \DateTime("last monday of December " . $this->year));
        $period   = new \DatePeriod(new \DateTime($this->year."-01-04"), $interval, new \DateTime($this->year."-12-28"));

        foreach ($period as $dt) {
            $astreinte = null;
            // Recherche si la prochaine Astreinte est égale a la semaine actuelle
            if($this->getNextAstreinte() != null && $dt->format("W") == $this->getNextAstreinte()->getSemaine()){
                $astreinte = $this->getNextAstreinte();
                $this->defineNextAstreinte();
            }            

            $week = new Week(new \DateTime($dt->format("Y-m-d")), $astreinte);
            $weeks[$this->frDate($dt)][] = $week;
            $this->weeks[] = $week;
        }

        return $weeks;
    }

    public function frDate($date)
    {
        return strftime("%B", $date->getTimestamp());
    }
}