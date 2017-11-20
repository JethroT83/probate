<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ReportReady as Email;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;

class Notification extends Controller{

	public $fileID;

	public function __construct(int $fileID){

		$this->fileID = $fileID;
	}


	public function mail($user){

		### CACHE FILE ###
		Run::cacheFile($this->fileID);

		$fileInfo = Cache::get('file_info');

		Mail::to($user)->send(new Email($fileInfo->frontend_name));
	}

	public function updateStart(){

		DB::table('files')
            ->where('id', $this->fileID)
            ->update([	'ran' => 2,
            			'ran_time'=> date("Y-m-d H:i:s"),
            			'updated_at'=>date("Y-m-d H:i:s")]);
	}

	public function updateEnd(){

		DB::table('files')
            ->where('id', $this->fileID)
            ->update([	'ran' => 1,
            			'updated_at'=>date("Y-m-d H:i:s")]);
	}
}
