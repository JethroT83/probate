<?php

namespace App\Core;
use \App\Core\Services\ParseService as Parse;
class A_parseDocket implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        return Parse::parseKeyWord($this->text,'Docket Number:', 6, array(1,5));
    }
    
    # LEVEL 2 #
    public function parseLevel2(){
		#exec("sudo chmod 777 ".root." -R");
		        //Delete it at current page
		        #run::unlinkPage(cache::getCache("page"));

		$imageFile  = root."/storage/cache/build_p5.jpg";
		$pdfFile    = root."/storage/cache/build_p5.pdf";
		@unlink($imageFile);
		@unlink($pdfFile);
		        //Convert JPG again, increasing the quality from 100 to 300
		        run::convertToJPG($pdfFile, $imageFile , $density = 413, $quality = 300);

		$txtFile    = root."/storage/cache/build_p5.txt";
		@unlink($txtFile);

        //Convert to TXT
        run::readJPG($imageFile);

        //Reparse
        return $this->parseLevel1();

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