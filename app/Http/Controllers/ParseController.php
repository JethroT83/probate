<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\AddressService as Address;
use Illuminate\Http\Request;

class ParseController extends Controller{

	protected static $text;
	protected static $parseClasses;

	public function __construct($text){

		$textFile   = Cache::get('textFile');
		self::$text = file_get_contents($textFile);
	}

	public function parseLevel1(){

		##	Address Service	##
		$decAddress  = Address::getDeceasedAddress(self::$text);
		$proAddress  = Address::getProbateAddress(self::$text);

		Cache::put('decAddress',$decAddress,10);
		Cache::put('proAddress',$proAddress,10);

		// If the information Google returns is bad, then there is no need for it.
		if(Address::testGoogleAddress(self::$text,0) === false){Cache::forget('decAddress');}
		if(Address::testGoogleAddress(self::$text,1) === false){Cache::forget('proAddress');}


		foreach(self::$parseClasses as $column => $parseClass){

			Cache::put('column',$column,10);

			$parseClass = "\App\Core\\".$parseClass;

			// Call Parsing Class
			$CLASS = new $parseClass;
			
			// Set Text File
			$CLASS->text = self::$text;

			//Get Result 
			$r = $CLASS->parseLevel1();

			//Test Result
			$test = $CLASS->testLevel1($r);

			// Attach result
			if($test === true){	$result[$column] = $r;
			}else{				$result[$column] = -1;} // -1 is used because in a CSV it is more clear flag than false, which will be null

			Cache::put("result",$result,20);

		}

		return $result;
	}



	public function handle(){

		self::$parseClasses = array('Docket'=>'A_parseDocket',
									'CaseType'=>'B_parseCaseType',
									'ProbateDate'=>'C_parseProbateDate',
									'DateofDeath'=>'D_parseDeathDate',
									'DecdFullNamePulled'=>'E_parseDeceasedName',
									'DecdLastAddress'=>'F_parseDeceasedAddress',
									'DecdLastCity'=>'G_parseDeceasedCity',
									'DecdLastState'=>'H_parseDeceasedState',
									'DecdLastZip'=>'I_parseDeceasedZip',
									'PRFullNamePulled'=>'J_parseProbateName',
									'PRAddress'=>'K_parseProbateAddress',
									'PRCity'=>'L_parseProbateCity',
									'PRState'=>'M_parseProbateState',
									'PRZip'=>'N_parseProbateZip'
									);

		return $this->parseLevel1();

	}	
}