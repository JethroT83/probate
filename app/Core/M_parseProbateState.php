<?php

namespace App\Core;

class M_parseProbateCity implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        $lines  = Parse::removeShortLines($this->text,10);
        $line   = Parse::getProbateLine($lines);

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

    public function testLevel1(){


    }


}