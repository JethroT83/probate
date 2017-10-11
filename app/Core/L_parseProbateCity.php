<?php

namespace App\Core;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;

class L_parseProbateCity implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        $address = Cache::get('proAddress');
        return $address['city'];

        /*$lines  = Parse::removeShortLines($this->text,10);
        $lines  = Parse::indexArray($lines);
        $line   = Parse::getProbateLine($lines);

        if($line === false){return false;}

        $a = Address::getStreetEndingIndex($line) + 1;
        $b = Address::getStateIndex($line);

        if($a === false || $b === false){
           return false;
        }else{
            $c = Parse::sliceLine($line,$a,$b);//Get City in the line
            return trim(str_replace(',','',$c));//Remove the comma, if it is here
        }*/

    }
    
    # LEVEL 2 #
    public function parseLevel2(){


    }



    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################

    public function testLevel1($result){
        return true;

    }


}