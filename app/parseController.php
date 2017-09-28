<?php

namespace app{
use \app\services\addressService as address;
#use \app\services\nameService as name;
	Class parseController{

		public function __construct($text){

			$this->text = $text;
		}

		public function onController(){
			$docket 			= new \app\providers\A_parseDocket($this->text);
			/*$caseType 			= new \app\providers\B_parseCaseType($this->text);
			$probateDate 		= new \app\providers\C_parseProbateDate($this->text);
			$deathDate 			= new \app\providers\D_parseDeathDate($this->text);
			$deceasedName 		= new \app\providers\E_parseDeceasedName($this->text);
			$deceasedAddress 	= new \app\providers\F_parseDeceasedAddress($this->text, address::getZip());
			$probateName 		= new \app\providers\J_parseProbateName($this->text);
			$probateAddress 	= new \app\providers\K_parseProbateAddress($this->text, address::getZip());
			*/
			#$out['CaseType'] = $caseType->result;
			$out['Docket'] = $docket->result;
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

			return $out;
		}

	}
}