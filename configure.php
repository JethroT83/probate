<?php


class configure{

//-----------static parameters---------------	


	public function sql_credentials($t){
		switch($t){
			case 0://localhost
				$this->creds['server']   = 'localhost';
				$this->creds['username'] = 'pcguardm_magento';
				$this->creds['password'] = '$3riousFUM+';
				$this->creds['database'] = 'pcguardm_wallstreet';
				$this->creds['cart']	 = 0;
				$this->creds['key']	 	 = '';
				break;
			case 1:
				$this->creds['server']   = 'localhost:3307';
				$this->creds['username'] = 'root';
				$this->creds['password'] = '';
				$this->creds['database'] = 'probate';
				$this->creds['cart']	 = 1;
				$this->creds['key']	 	 = '';
				break;
			case 2:
				$this->creds['server']   = '';
				$this->creds['username'] = '';
				$this->creds['password'] = '';
				$this->creds['database'] = '';
				$this->creds['cart']	 = 0;
				break;
				
		}
		return $this->creds;
	}
	

}


?>