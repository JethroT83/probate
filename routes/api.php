<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
#use App\Http\Requests\LoginRequest;

############################ RUN ##########################
use App\Jobs\A_BreakPDF;
use App\Jobs\B_ConvertToText;
use App\Jobs\C_ParseText;
use App\Jobs\D_CleanUp;
use Carbon\Carbon;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\EachPromise;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api'
  ], function () {

      Route::post('/auth/register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register'
      ]);

      Route::post('/auth/login', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login'
      ]);

      Route::post('/upload', [
        'as'=>'upload',
        'uses' => 'FileController@store'
      ]);

      Route::get('/download/{fileID}',function(Request $request){
        
        $fileID   = $request->fileID;
        $D        = new \App\Http\Controllers\Api\DownloadController($fileID);

        return    $D->handle();
      });

      Route::get('/files','UploadController@get');


      Route::get('/run/{fileID}',function(Request $request){

        $fileID = $request->fileID;

        $job1 = (new A_BreakPDF($fileID))->delay(Carbon::now()->addSeconds(5));
        $job2 = (new B_ConvertToText($fileID));
        $job3 = (new C_ParseText($fileID));
        $job4 = (new D_CleanUp($fileID));

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

      });
});