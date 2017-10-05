<?php

namespace App\Core;

class B_parseCaseType implements _Contract{

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################

    private static $caseTypes = array('PROBATE','SPOUSE AFFIDAVAID','NEXT OF KIN');

    public function parseLevel1(){

        // Break out Lines
        $lines = explode("\n",$this->text);

         // Case Type is normally on line 3
         for($i=0,$i<5,$i++){
            $line = $lines[$i];
            foreach(self::$caseTypes as $i => $caseType){
                if(stripos($line,$caseType)){
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
    public function testLevel1(){
        if(array_search($this->result,$this->caseTypes)!==false){
            return 1;
        }else{
            return -1;
        }
    }
    

}