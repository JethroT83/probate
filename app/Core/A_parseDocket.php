<?php

namespace App\Core;
use \App\Core\Services\ParseService as Parse;
class A_parseDocket implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){

        $lines  =  Parse::removeShortLines($this->text, 10);
        $text   =  Parse::implodeLines($lines);
        return Parse::parseKeyWord($text,'Docket Number:', 6, array(1,5));
    }
    
    # LEVEL 2 #
    public function parseLevel2(){

    }



    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################

    public function testLevel1($result){

        // Docket is a 6 digit number
        if( strlen($result) == 6 && is_numeric($result) ){
            return true;
        }else{
            return false;
        }
    }


}