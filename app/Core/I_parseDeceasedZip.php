<?php

namespace App\Core;

use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;

class I_parseDeceasedZip implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){

        $lines  =  Parse::removeShortLines($this->text, 10);
        $text   =  Parse::implodeLines($lines);
        $string =  Parse::parseKeyWord($text,'Address:', null, array(2,5));

        // String returns -- Address, City, State Zip
        $e          = explode(",",$string);
        $zipState   = preg_replace('/\s+/','',$e[2]);

        $zip      = substr($zipState,2,5);

        #if(in_array($zip, Address::$zip)){
        #    return $zip;
        #}else{
        #    return false;
        #}
        
        return $zip;
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