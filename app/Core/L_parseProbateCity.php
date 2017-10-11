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

        //Remove bad lines
        $lines  = Parse::removeShortLines($this->text,10);
        
        //Re-index array
        $lines  = Parse::indexArray($lines);

        //Find line with the probate information
        $line   = Parse::getProbateLine($lines);
        if($line === false){return false;}

        //Find where the address begins
        $a = Parse::findNumber($line,true);

        //Shorten the line to where the address number starts
        $shortLine = Parse::sliceLine($line,$a);

        //Count Commas
        $c = substr_count($shortLine,",");

        switch($c){

            //If there is one comma, it is 'city, state'
            case 1:
                $e = explode(",",$shortLine);// break address city, state
                $addressCity = trim($e[0]);

                $e = explode(" ",$addressCity);// break address city into words
                $e = array_slice($e,0,-1);// City is at least two words, one can be removed


                //Going from right to left, search for a street ending
                $w = count($e)-1;
                for($i=$w;$i>0;$i--){

                    $word = $e[$i];

                    if(Address::isStreetEnding($word)){
 
                        //When a street ending is found, get the begining of the short line to the street ending
                        $city = Parse::sliceLine($addressCity,$i+1);

                        return Address::googleVerify($city,"city",1);

                    }
                }
                break;

            case 2:

                $e      = explode(",",$shortLine);// break address apt city, state
                $aptCity = trim($e[1]);

                $e      = explode(" ",$aptCity);// break address city into words
                $e      = array_slice($e,1);//Remove apartment part

                $city   = implode(" ",$e); // implode back into a string

                return Address::googleVerify($city,"city",1);

                break;
        }


        return false;
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