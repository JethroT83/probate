<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(120000);
ini_set('memory_limit','2048M');

require_once(__DIR__."/vendor/autoload.php");

#echo "\n\n".__LINE__."--declared classes-->" .json_encode(get_declared_classes(), JSON_PRETTY_PRINT);

$file = __DIR__."/resources/build.pdf";
echo is_file($file);
$P =  new \app\providers\parse($file);
