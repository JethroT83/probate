<?php

namespace App\Core;
use \App\Core\Services\ParseService as Parse;
use Illuminate\Support\Facades\Cache as Cache;
class B_parseCaseType implements _Contract{

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################

    private static $caseTypes = array('PROBATE','SPOUSE AFFIDAVIT','NEXT OF KIN AFFIDAVIT');

    public function parseLevel1(){

        // Break out Lines
        $lines = Parse::breakLines($this->text);

        // Case Type is normally on line 3
         for($i=0;$i<5;$i++){
            $line = $lines[$i];
            foreach(self::$caseTypes as $j => $caseType){

                switch($line){

                    case (stripos($line,$caseType) !==false):
                        return $caseType;

                    // The OCR messes up F's a lot.  This logic side steps that issue
                    case (stripos($line,'Case Type:') && (stripos($line,'Spouse') !==false)):
                        return 'SPOUSE AFFIDAVIT';

                    case (stripos($line,'Case Type:') && (stripos($line,'next of kin') !==false)):
                        return 'NEXT OF KIN AFFIDAVIT';

                }
            }
         }

        //The case type was not found
        return false;
    }


    public function parseLevel2(){

    }




    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
    public function testLevel1($result){
        return true;
    }
    

}