<?php

namespace App\Core;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class H_parseDeceasedState implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        $address = Cache::get('proAddress');
        return $address['state'];

        /*$lines  =  Parse::removeShortLines($this->text, 10);

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

        $zipState =false;
        switch($e){

            case (count($e) == 3):
                $zipState = trim($e[2]);
                break;

            case (count($e) == 4):
                $zipState = trim($e[3]);
                break;
        }

        if($zipState === false){return false;}
        $state      = substr($zipState,0,2);

        if(in_array($state, Address::$states)){
            return $state;
        }else{
            return false;
        }*/
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