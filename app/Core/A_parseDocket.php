<?php

namespace App\Core;

class A_parseDocket implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


    # LEVEL 1 #
    public function parseLevel1(){
        $lines = explode("\n",$this->text);

        foreach($lines as $i => $line){
            $_line = str_replace(' ', '', $line);

            if(stripos($_line, "DocketNumber:")!==false){
                $a = stripos($_line, "DocketNumber:") + 13;
                $docket = substr($_line,$a,6);

                return trim($docket);
            }
        }
    }
    
    # LEVEL 2 #
    public function parseLevel2(){
		exec("sudo chmod 777 ".root." -R");
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

    public function testLevel1(){
        $out    = cache::getCache("out");
        $page   = cache::getCache("page");

        if($docket > $out[$page-1]){
            return true;
        }else{
            return false;
        }

    }


}