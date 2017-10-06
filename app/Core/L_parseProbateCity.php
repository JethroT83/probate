<?php

namespace App\Core;

class L_parseProbateCity implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){

        $lines  = Parse::removeShortLines($this->text,10);
        $line   = Parse::getProbateLine($lines);

        $a = Parse::findStreetEndingIndex($line) + 1;
        $b = Address::getStateIndex($line);


        if($a === false || $b === false){
            return false;
        }else{

            $c = Parse::sliceLine($line,$a,$b);//Get City in the line
            return trim(str_replace(',','',$c));//Remove the comma, if it is here

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