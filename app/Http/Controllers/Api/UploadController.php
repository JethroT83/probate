<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
	
	public function get(){

		return DB::table('files')
				->select('id','local_name','frontend_name','ran','upload_time','ran_time')
				->where('delete',0)
				->get();
	}


	public function post($local_name, $frontend_name){

		DB::table('files')->insert(
		    ['local_name' 	=> $local_name, 
		    'frontend_name' => $frontend_name,
		    'upload_time'	=> date("Y-m-d H:i:s")]
		);

	}


	public function store(Request $request){

		$frontend_name 	= $request->file('file')->getClientOriginalName();
		$response 		= $request->file('file')->store('uploads','local');
		
		$e = explode("/",$response);
		$local_name = end($e);

		$this->post($local_name,$frontend_name);

		return $response;
	}
}
