<?php

$string = " n Barbara T. Alexander";
echo substr($string, 0, 1);
$s = "";

for($x=0;$x<=strlen($string);$x++){
	$l = substr($string, $x, 1);
    echo ctype_upper($l);
	if(ctype_upper($l)==true){
		$start = $x;
		break;
	}
}
 $string = substr($string,$start);



?>