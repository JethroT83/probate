<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#Route::get('/', function () {
#    return view('welcome');
#});
Route::get('/','FileController@index');
Route::post('/store','FileController@store')->name('file.store');


#Route::get('/', function () {return view('file.index');});

#Route::get('/run','RunController@handle')->name('run');


############################ RUN ##########################
use App\Jobs\A_BreakPDF;
use App\Jobs\B_ConvertToText;
use App\Jobs\C_ParseText;
use App\Jobs\D_CleanUp;
use Carbon\Carbon;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\EachPromise;

# Converting the PDF into a file can take upto an hour
# For this reason the whole process is broken down into smaller parts
# This way, if the server is interupted, it can simply pick up where it left off
Route::get('/run',function(){

	$file = base_path()."/storage/app/build.pdf";

	$job1 = (new A_BreakPDF($file))->delay(Carbon::now()->addSeconds(5));
	$job2 = (new B_ConvertToText($file));
	$job3 = (new C_ParseText($file));
	$job4 = (new D_CleanUp($file));

	$promises = [dispatch($job1),dispatch($job2),dispatch($job3),dispatch($job4)];

	$each = new EachPromise($promises, [
	    'fulfilled' => function ($value, $id, Promise $aggregate) use (&$called) {
	        $aggregate->resolve(null);
	    },
	    'rejected' => function (\Exception $reason) {
	        echo $reason->getMessage();
	    }
	]);

	foreach($each->promise() as $i => $prom){
		$prom->resolve();
		$prom->wait();
	}



})->name('run');


Route::post('/store','FileController@store')->name('file.store');