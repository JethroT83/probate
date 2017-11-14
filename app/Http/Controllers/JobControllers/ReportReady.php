<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ReportReady as Email;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ReportReady extends Controller{

	public function __construct($fileID){
		$this->fileID = $fileID;
	}


	public function mail(Request $request){
		
		//$user = $auth::user();

		$user = $request->user();
		file_put_contents("user.txt",$user);


		Mail::to($request->user())->send(new Email());
	}
}
