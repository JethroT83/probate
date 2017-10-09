<?php

namespace App\Core;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class G_parseDeceasedCity implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        
        $string =  Parse::parseKeyWord($this->text,'Address:', null, array(2,5));

        // String returns -- Address, City, State Zip
        $e      = explode(",",$string);

        if(is_numeric($e[1]) || strlen($e[1]) == 0){
            return false;
        }else{
            return trim($e[1]);  
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