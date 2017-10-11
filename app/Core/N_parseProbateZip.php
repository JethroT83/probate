<?php

namespace App\Core;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;

class N_parseProbateZip implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){

        $lines  = Parse::removeShortLines($this->text,10);
        $lines  = Parse::indexArray($lines);
        $line   = Parse::getProbateLine($lines);

        if($line === false){return $line;}

        $a = Address::findZip($line);

        if($a === false){
            $zLine = Address::retrieveStateZipLine($this->text);
            if(isset($zLine['zip'])){
                return $zLine['zip'];
            }else{
                return Address::googleVerify(null,'zip',1);
            }

        }else{
            return Parse::sliceLine($line,$a,$a);//Get City in the line
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