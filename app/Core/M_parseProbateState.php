<?php

namespace App\Core;

use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;

class M_parseProbateState implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        $lines  = Parse::removeShortLines($this->text,10);
        $lines  = Parse::indexArray($lines);
        $line   = Parse::getProbateLine($lines);

        if($line === false){return false;}

        $a = Address::getStateIndex($line);

        if($a === false){
            return false;
        }else{

            return Parse::sliceLine($line,$a,$a+1);//Get City in the line
        }

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