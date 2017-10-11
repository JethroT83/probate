<?php

namespace App\Core;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class G_parseDeceasedCity implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){

        $lines  =  Parse::removeShortLines($this->text, 10);

        foreach($lines as $i => $line){
            $pos = stripos($line,'address:');
            if(stripos($line,'address:') !== false){
                $string = trim(substr($line,8));
                break;
            }

            if($i == 8){break;}
        }

        // String returns -- Address, City, State Zip
        $e      = explode(",",$string);

        $address =false;
        switch($e){

            case (count($e) == 3):
                $address = trim($e[1]);
                break;

            case (count($e) == 4):
                $address = trim($e[2]);
                break;
        }


        return $address;
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