<?php

namespace App\Core;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class H_parseDeceasedState implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        
        $string =  Parse::parseKeyWord($this->text,'Address:', null, array(2,5));

        // String returns -- Address, City, State Zip
        $e          = explode(",",$string);
        $zipState   = preg_replace('/\s+/','',$e[2]);

        $state      = substr($zipState,0,2);

        if(in_array($state, Address::$states)){
            return $state;
        }else{
            return false;
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