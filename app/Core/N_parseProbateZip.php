<?php

namespace App\Core;

class N_parseProbateCity implements _Contract{


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

            return Parse::sliceLine($line,$a+1,$a+2);//Get City in the line
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