<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(120000);
ini_set('memory_limit','2048M');

require_once(__DIR__."/vendor/autoload.php");

$file = __DIR__."/resources/build.pdf";
$P =  new \app\providers\parse($file);
