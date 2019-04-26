<?php

namespace App\Entity;

class BasicFunction
{
    public static function astreinteWithDate($astreintes, $date)
    {
        foreach($astreintes as $astreinte){
            if($astreinte->getAnnee() == $date->format("Y") && $astreinte->getSemaine() == $date->format("W")){
                return $astreinte;
            }
        }
        return null;
    }
}