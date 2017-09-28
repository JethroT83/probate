<?php

namespace app{
Class textParser{

	protected static $name;

	public function __construct($text){
		$this->text = $text;
	}


	public function parseProbate($text){
		$probateDate = new \app\providers\C_parseProbateDate($text);
		$deathDate = new \app\providers\D_parseDeathDate($text);
		$deceasedName = new \app\providers\E_parseDeceasedName($text);
		$deceasedAddress = new \app\providers\F_parseDeceasedAddress($text, $this->zip);
		$probateName = new \app\providers\J_parseProbateName($text);
		$probateAddress = new \app\providers\K_parseProbateAddress($text, $this->zip);
		
		$this->out['CaseType'] = $this->caseType;
		$this->out['ProbateDate'] = $probateDate->result;
		$this->out['DateofDeath'] = $deathDate->result;
		$this->out['DecdFullNamePulled'] = $deceasedName->result;
		$this->out['DecdLastAddress'] = $deceasedAddress->result['street'];
		$this->out['DecdLastCity'] = $deceasedAddress->result['city'];
		$this->out['DecdLastState'] = $deceasedAddress->result['state'];
		$this->out['DecdLastZip'] = $deceasedAddress->result['zip'];
		$this->out['PRFullNamePulled'] = $probateName->result;
		$this->out['PRAddress'] = $probateAddress->result['street'];
		$this->out['PRCity'] = $probateAddress->result['city'];
		$this->out['PRState'] = $probateAddress->result['state'];
		$this->out['PRZip'] = $probateAddress->result['zip'];
		
	}
	
	public function parseNextofKin($text){
		$probateDate = new \app\providers\C_parseProbateDate($text);
		$deathDate = new \app\providers\D_parseDeathDate($text);
		$deceasedName = new \app\providers\E_parseDeceasedName($text);
		$deceasedAddress = new \app\providers\F_parseDeceasedAddress($text, $this->zip);
		$probateName = new \app\providers\J_parseProbateName($text, "Af");	
		$probateAddress = new \app\providers\K_parseProbateAddress($text, $this->zip);
		

		$this->out['CaseType'] = $this->caseType;
		$this->out['ProbateDate'] = $probateDate->result;
		$this->out['DateofDeath'] = $deathDate->result;
		$this->out['DecdFullNamePulled'] = $deceasedName->result;
		$this->out['DecdLastAddress'] = $deceasedAddress->result['street'];
		$this->out['DecdLastCity'] = $deceasedAddress->result['city'];
		$this->out['DecdLastState'] = $deceasedAddress->result['state'];
		$this->out['DecdLastZip'] = $deceasedAddress->result['zip'];
		$this->out['PRFullNamePulled'] = $probateName->result;
		$this->out['PRAddress'] = $probateAddress->result['street'];
		$this->out['PRCity'] = $probateAddress->result['city'];
		$this->out['PRState'] = $probateAddress->result['state'];
		$this->out['PRZip'] = $probateAddress->result['zip'];
		
		
		
	}
	
	public function parseText(){
		$text = $this->text;
		$docket = new \app\providers\A_parseDocket($text);
				
		$caseType = new \app\providers\B_parseCaseType($text);

		$this->out['ProbateType'] = "val";
		$this->out['Docket'] = $docket->result;
		
		switch($caseType->result){
			case "PROBATE":
				$this->caseType = "PROBATE";
				$this->parseProbate($text);
				break;
			case "NEXT OF KIN AFFIDAVIT":
				$this->caseType = "NEXT OF KIN AFFIDAVIT";
				$this->parseNextofKin($text);
				break;
			case "SPOUSE AFFIDAVIT":
				$this->caseType = "SPOUSE AFFIDAVIT";
				$this->parseNextofKin($text);
				break;
		}	
	}
}
}