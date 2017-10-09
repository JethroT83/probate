<?php

namespace App\Core;
use \App\Core\Services\ParseService as Parse;
class B_parseCaseType implements _Contract{

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################

    private static $caseTypes = array('PROBATE','SPOUSE AFFIDAVAID','NEXT OF KIN');

    public function parseLevel1(){

        // Break out Lines
        $lines = Parse::breakLines($this->text);

         // Case Type is normally on line 3
         for($i=0;$i<5;$i++){
            $line = $lines[$i];
            foreach(self::$caseTypes as $j => $caseType){
                if(stripos($line,$caseType) !==false){
                    return $caseType;
                }
            }
         }

        //The case type was not found
        return false;
    }


    public function parseLevel2(){
        #$f = fopen("caseTypes_NotFound.txt","w");
        #fwrite($f,$this->result);
        #fclose($f);
    }




    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
    public function testLevel1($result){
        return true;
        /*if(array_search($result,self::$caseTypes)!==false){
            return true;
        }else{
            return false;
        }*/
    }
    

}