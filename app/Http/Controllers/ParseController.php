<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache as Cache;
use Illuminate\Http\Request;

class ParseController extends Controller{

	protected static $text;
	protected static $parseClasses;

	public function __construct($text){

		$textFile   = Cache::get('textFile');
		self::$text = file_get_contents($textFile);
	}


	public function parseLevel1(){

		foreach(self::$parseClasses as $column => $parseClass){

			// Call Parsing Class
			$CLASS = new \App\Core\$parseClass;
			
			// Set Text File
			$CLASS->text = self::$text;
			
			//Get Result 
			$r = $CLASS->parseLevel1();

			//Test Result
			$test = $CLASS->testLevel1();

			// Attach result
			if($test === true){	$result[$column] = $r;
			}else{				$result[$column] = -1;} // -1 is used because in a CSV it is more clear flag than false, which will be null

		}

		return $result;
	}



	public function onController(){

		self::$parseClasses = array('docket'=>'A_parseDocket',
									'caseType'=>'B_parseCaseType',
									'ProbateDate'=>'C_parseProbateDate',
									'DeathDate'=>'D_parseDeathDate',
									'DeceasedName'=>'E_parseDeceasedName',
									'DeceasedAddress'=>'F_parseDeceasedAddress',
									'ProbateName'=>'J_parseProbateName',
									'ProbateAddress'=>'K_parseProbateAddress');

		$result = $this->parseLevel1();

	}	
}


#$docket 			= new \App\Core\A_parseDocket(self::$text);
/*$caseType 		= new \App\Core\B_parseCaseType($this->text);
$probateDate 		= new \App\Core\C_parseProbateDate($this->text);
$deathDate 			= new \App\Core\D_parseDeathDate($this->text);
$deceasedName 		= new \App\Core\E_parseDeceasedName($this->text);
$deceasedAddress 	= new \App\Core\F_parseDeceasedAddress($this->text, address::getZip());
$probateName 		= new \App\Core\J_parseProbateName($this->text);
$probateAddress 	= new \App\Core\K_parseProbateAddress($this->text, address::getZip());
*/
#$out['CaseType'] = $caseType->result;
#$out['Docket'] = $docket->result;
/*$out['ProbateDate'] = $probateDate->result;
$out['DateofDeath'] = $deathDate->result;
$out['DecdFullNamePulled'] = $deceasedName->result;
$out['DecdLastAddress'] = $deceasedAddress->result['street'];
$out['DecdLastCity'] = $deceasedAddress->result['city'];
$out['DecdLastState'] = $deceasedAddress->result['state'];
$out['DecdLastZip'] = $deceasedAddress->result['zip'];
$out['PRFullNamePulled'] = $probateName->result;
$out['PRAddress'] = $probateAddress->result['street'];
$out['PRCity'] = $probateAddress->result['city'];
$out['PRState'] = $probateAddress->result['state'];
$out['PRZip'] = $probateAddress->result['zip'];*/

#return $out;