<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class UploadController extends Controller
{
	private $user;

	public function __construct(Request $request){
		$this->user = $request->user();
	}

	public function get(){

		return DB::table('files')
				->select('id','local_name','frontend_name','ran','upload_time','ran_time')
				->where('delete',0)
				->where('user_id',$this->user->id)
				->get();
	}


	public function post($local_name, $frontend_name){

		DB::table('files')->insert(
		    ['user_id'		=> $this->user->id,
		    'local_name' 	=> $local_name, 
		    'frontend_name' => $frontend_name,
		    'upload_time'	=> date("Y-m-d H:i:s"),
		    'created_at'	=> date("Y-m-d H:i:s"),
		    'updated_at'	=> date("Y-m-d H:i:s")]
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
