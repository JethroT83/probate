<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetupService as Setup;

class JobTest extends TestCase{

    private static $dir;
    private static $file;
    private static $control;

    protected function setUp(){

        # Larael
        parent::setup();

        # Basic Unit Setup
        Setup::setUpUnit();

        # Copies the data set to the storage Folder
        Setup::copy_files();

        # Get the Controled Variables form the dataSet folder
        self::$control = Setup::getJob();

        self::$file = base_path()."/storage/app/build.pdf";
        self::$dir  = base_path()."/storage/app/";
    }


    protected function tearDown(){

        //Remove build out file, if it exists
        @unlink(base_path()."/storage/app/build_out.csv");
    }

    public function testBreakPDFJob(){
        
        $A = new \App\Jobs\A_BreakPDF(self::$file);
        $A->handle();

        $test = count(glob(self::$dir . "*.pdf",GLOB_BRACE));
        $control = self::$control['breakPDFJob'];

        $this->assertEquals($control,$test,"Should contrlled number of pdfs after pages are broken out.");
    }

    public function testConvertToText(){
        
        $A = new \App\Jobs\B_ConvertToText(self::$file);
        $A->handle();


        $testJPG    = count(glob(self::$dir . "*.jpg",GLOB_BRACE));
        $controlJPG = self::$control['convertToText']['JPG'];

        $testTXT    = count(glob(self::$dir . "*.txt",GLOB_BRACE));
        $controlTXT = self::$control['convertToText']['TXT'];

        $this->assertEquals($controlJPG,$testJPG,"Should contrlled number of jpgs after pages are broken out.");
        $this->assertEquals($controlTXT,$testTXT,"Should contrlled number of txts after pages are broken out.");
    }

    public function testParseTextJob(){
        
        $A = new \App\Jobs\C_ParseText(self::$file);
        $A->handle();

        $test = count(glob(self::$dir . "*.csv",GLOB_BRACE));
        $control = self::$control['ParseTextJob'];

        $this->assertEquals($control,$test,"Should contrlled number of pdfs after pages are broken out.");
    }

    public function testCleanUpJob(){
        
        $A = new \App\Jobs\D_CleanUp(self::$file);
        $A->handle();

        $testPDF = count(glob(self::$dir . "*.pdf",GLOB_BRACE));
        $testJPG = count(glob(self::$dir . "*.jpg",GLOB_BRACE));
        $testTXT = count(glob(self::$dir . "*.txt",GLOB_BRACE));

        $this->assertEquals(1,$testPDF,"There should only be the build PDF in storage.");
        $this->assertEquals(0,$testJPG,"There should not be any jpg files in the folder.");
        $this->assertEquals(0,$testTXT,"There should not be any txt files in the folder.");
    }

}
