<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Core\Services\RunService as Run;
class DownloadController extends Controller
{
    //
    public function __construct($fileID){
    	$this->fileID = $fileID;
    }


    public function handle(){

    	return Run::getReportByFile($this->fileID);
    }
}
