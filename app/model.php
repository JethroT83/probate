<?php
namespace app{
Class model{
	public $result;



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



	public function now(){
		date_default_timezone_set("America/New_York");
		return $this->N		= date("Y-m-d H:i:s");
	}
	
	public function cart($t){
		switch($t){
			case 0:
				return $cart = 'x_cart';
			case 1:
				return $cart = 'Magento';
		}
	}

		
/*
This connects to the database
if $t==0 then it will connect to the localhost
if $t==1 then it will connect to the jstqi
*/	
	function error($file = null, $line = null){
		$error	=	"File: " . $file;
		$error	.=	" Line: " . $line;
		return $error;
	}	
	
	/*function connect($t, $error){
		$this->sql_credentials($t);
		if(isset($this->creds)==1){
			$this->link =	mysql_connect($this->creds['server'], $this->creds['username'],$this->creds['password'])or die( $this->error_connect = 'MySQL Error: ' . mysql_errno()."<br>". $error );
			mysql_select_db($this->creds['database'], $this->link);	
				
			if(isset($this->error_connect)==1){
				return $this->error_connect;
			}else{
				return $this->link;
			}
		}else{
			return $this->error_connect = "Error: Database parameter is not recognized";
		}
	}*/
	
	
	function connect($t, $error){
		$this->sql_credentials($t);
		if(isset($this->creds)==1){
			$this->link =	mysqli_connect($this->creds['server'], $this->creds['username'],$this->creds['password'],$this->creds['database'])or die( $this->error_connect = 'MySQL Error: ' . mysql_errno()."<br>". $error );
			//mysql_select_db(, $this->link);	
				
			if(isset($this->error_connect)==1){
				return $this->error_connect;
			}else{
				return $this->link;
			}
		}else{
			return $this->error_connect = "Error: Database parameter is not recognized";
		}
	}
	
	
	
	function take_out_iterator($get){
		$i = 0;
		while($r 	= 	mysqli_fetch_assoc($get)){
				if($i==0){
					$this->result = $r;
				}else if($i==1){
					$place = $this->result;
					$this->result = '';
					$this->result[0] = $place;
					$this->result[1] = $r;
				}else if($i>=2){
					$this->result[$i] = $r;
				}
				$i++;	
		}	
		return 	$this->result;
	}
	
	function iterator($get){

		$i=0;
		while($r = 	mysqli_fetch_assoc($get)){
				foreach ($r as $key => $value){
					$this->result[$i][$key] = $value;
				}
				$i++;
			}

		return $this->result;
	}
	
	//this function reads
	//if iterator is set to 0, the output will on have an iterator if there is one result
	function read($t, $query,  $file = __file__,  $line = __line__, $iterator = true){
		
		if(isset($this->result)==1){
			unset($this->result);
		}
		
		$error = $this->error($file ,  $line );
		$this->connect($t, $error);
	
		if(isset($this->error_connect)==0){
			$get		= 	mysqli_query($this->link,$query) or die( 'MySQL Error: ' . mysqli_errno($this->link)."<br>".mysqli_error($this->link)."<br>". $error );
			$this->rows = 	mysqli_num_rows($get);

			if($this->rows>0){
				if($iterator === false OR $iterator == 0){	
					$this->take_out_iterator($get);
					return $this->result;
				}else if($iterator === true OR $iterator == 1){
					$this->iterator($get);
					return $this->result;
				}
			}else{
				$this->result	=	mysqli_fetch_assoc($get);
				return $this->result;
			}
		}else{
			return $this->error_connect;
		}
	}

	
	function record($t, $query, $file = __file__, $line = __line__){
		$error = $this->error($file,  $line);
		$this->connect($t, $error);
		
		if(isset($this->error_connect)==0){
			$get	= 	mysqli_query($this->link,$query) or die( 'MySQL Error: ' . mysqli_errno($this->link)."<br>".mysqli_error($this->link)."<br>". $error);
			return $get;
		}else{
			return $this->error_connect;
		}
	}	
	
	//this decompresses zipped files
	function descomprime($archivo, $extractto = "./", $class = __class__, $function = __function__, $line = __line__ ){
		$zip = new ZipArchive;
		if($zip->open($archivo) === TRUE){
			$zip->extractTo($extractto);
			$zip->close();
			return "Success";
		}else{
			echo "Error: Class--".$class."  function---".$function."  line----".$line;
		}
	}
	
	//this retrieves a file from an FTP server
	function getfile($id_supplier, $file_name = NULL){
		$this->ftp_credentials($id_supplier);
		
		echo "<br><br>These are the FTP creds--->";
		print_r($this->ftpcreds);
		
		if(isset($file_name)==0){
			$file_name	=	$this->ftpcreds['supplier'];
		}	
		
		echo "This is directory--->";
		print_r(getcwd());
		
		$f 		= fopen("feed/".$file_name, "w+");
		$con 	= ftp_connect($this->ftpcreds['ftp_server']);
		$res 	= ftp_login($con, $this->ftpcreds['ftp_user'], $this->ftpcreds['ftp_psw']);
		
		//comando para el uso local 
		//command for using the localhost
		ftp_pasv($con, true);

		if(ftp_fget($con, $f, $this->ftpcreds['file_name'], FTP_BINARY, 0)){
			return "Success";
		}else{ 
			
			return "Error";
		}

		ftp_close($con);
		fclose($f);
		
	}
	
	
##PHP code reset every time AJAX is used.  This is inconvenient becuase all the variables reset.
##This function saves information that can be recalled from a later AJAX call.
##It does this by writing to a file.
	function global_session($function, $filename = 'session.txt', $info = null  ){
		switch($function){
			case 0://0 == write
				$f			=	fopen($filename, 'w');
				$info		=	json_encode($info);
				fwrite($f, $info);
				fclose($f);
				break;
			case 1://1 == read
				if(is_file($filename)!==false){
					$f			=	fopen($filename, 'r');
					$content	=	fread($f, '8192');
					return (array)json_decode($content);
				}else{
					return false;
				}
				break;
			case 2://2 == clear
				if(is_file($filename)!==false){
					$f		=	fopen($filename, 'r');
					file_put_contents($filename, '');
				}
				break;
		}
	}
	
	
	function array_to_CSV($array, $filename){
		$keys	=	array_keys($array[1]);
		$f		=	fopen($filename.".csv" ,'w');
		fputcsv ($f	 , $keys);
		
		foreach($array as $i => $info){
			fputcsv ($f	 , $info);
		}
		
		fclose($f);
	}
	
	/*function CSV_to_array($filename, $delimiter = ",", $enclosure='"'){
		if(is_file($filename) != false){
			$new	=	str_getcsv($filename, $delimiter, $enclosure);
			return $new;
		}else{
			return "not a file";
		}
	}*/
	
	function read_directory($cwd){
		if ($handle = opendir($cwd)) {
			$new = "";
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$new .= $entry ."<br>";
				}
			}
			closedir($handle);
			return $new;
		}
	}
	
	
	function CSV_to_array($filename, $header=true,$delimiter = ",", $enclosure='"' ){
		if ( ! is_file( $filename ) ){
				echo "<br><br>" .getcwd().$filename ."<br><br>";
				exit('File not found.');
		}else{
			if (($handle = fopen( $filename, "r")) !== FALSE){
				$i=0;
				$headers	=	"";
				while (($data = fgetcsv($handle, 10000, ",")) !== FALSE){
					if($headers!==false){
						if($i==0){
							$headers	=	$data;
						}else{					
							foreach($headers as $j => $head){
								$new[$i-1][$head]	=	$data[$j];
							}
						}
					}else{
						$new[$i]	=	$data;
					}
					$i++;
				}
				return $new;
			}
		}
	}
	
	function object_search($needle, $haystack, $lower=true){
		if($lower==true){
			foreach($haystack as $key=> $value){
				
				if(strtolower($value)==strtolower($needle)){
					return $key;
				}	
				
			}
		}else{
			foreach($haystack as $key=> $value){
				if($value==$needle){
					return $key;
				}		
			}
		}
		return -1;
	}
	
	
	function curl($url){
		$ch = curl_init($url);
		//$fap = fopen("testing.txt", "w");
		//curl_setopt($ch, CURLOPT_FILE, $fap);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		$output = curl_exec($ch);
		curl_exec($ch);
		curl_close($ch);
	
		return $output;
	}
	
	function curl_source($url){
		$ch = curl_init("http://www.example-webpage.com/file.html");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
	
		return $content;
	}
	
	
	
	function key1($array){
		$array	=	(array)$array;
		if(is_array($array)	!=false){
			$j=0;
			foreach($array as $key	=> $info){
				if($j==0){
					if($info == ""){
						return -1;
					}else{
						return $key;
					}
				}
			}
		}else{
			return -1;
		}
	}
	
	function count_($array){
		$j=0;
		foreach($array as $i => $info){
			$j++;
		}
		return $j;
	}
	
	
	function array_search_assoc($needle, $haystack){
		if(is_array($haystack)!=false){
			foreach ($haystack as $key => $value){
				if (strtolower(trim($needle)) == strtolower(trim($value))){
					return $key;
				}
			}
			return false;
		}else{
			return -1;
		}
	}
	
	

	function isJson($string) {
		json_decode($string);
		if(json_last_error() == JSON_ERROR_NONE){
			return true;
		}else{
			return -1;
		}
	}
	
	/*function isJson($string){
		return ((is_string($string) && 
				(is_object(json_decode($string)) || 
				is_array(json_decode($string))))) ? true : false;
	}*/

	function enclose($array){
		foreach($array as $i => $info){
			$new[$i]	=	addslashes($info);
		}
		return $new;
	}
	
	function array_search_C($needle, $haystack){
		foreach($haystack as $key => $value){
			if(stripos($value, $needle)!==false){
				return $key;
			}
		}
		return -1;
	}
	
	function array_key_C($needle, $haystack){
		foreach($haystack as $key => $value){
			if(stripos($key, $needle)!==false){
				return $key;
			}
		}
		return -1;
	}
	
	
	function is_alphanumeric($string){
		$m=0;
		$length	= strlen($string);
		for($s=0;$s<=$length;$s++){
			$letter	=	substr($string, $s, 1);
			if(ctype_alpha($letter)==1){
				$m = 1;
			}
		}
		for($s=0;$s<=$length;$s++){
			$letter	=	substr($string, $s, 1);
			if($m==1 && ctype_digit($letter)==1){
				return 1;
			}
		}
		return 0;
			
	}
	
}
}

?>