<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

	public function delete(){
		DB::table('files')
            ->where('id', $this->fileID)
            ->update([	'delete' => 1,
            			'updated_at'=>date("Y-m-d H:i:s")]);
	}
}
