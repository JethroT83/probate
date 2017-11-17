<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

############################ RUN ##########################
use App\Jobs\A_BreakPDF;
use App\Jobs\B_ConvertToText;
use App\Jobs\C_ParseText;
use App\Jobs\D_CleanUp;
use App\Jobs\E_Notification;
use Carbon\Carbon;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\EachPromise;

use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;
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

      # ADMIN ONLY
      #Route::post('/auth/register', [
      #  'as' => 'auth.register',
      #  'uses' => 'AuthController@register'
      #])

      Route::post('/auth/login', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login'
      ]);


      Route::post('/upload', [
        'as'=>'upload',
        'uses' => 'UploadController@store'
      ])->middleware('jwt.auth');


      Route::get('/download/{fileID}',function(Request $request){
        
        $fileID   = $request->fileID;
        $D        = new \App\Http\Controllers\Api\DownloadController($fileID);

        return    $D->handle();
      })->middleware('jwt.auth');


      Route::get('/files',function(Request $request){

        $U = new \App\Http\Controllers\Api\UploadController($request);
        return $U->get();

      })->middleware('jwt.auth');


      Route::get('/run/{fileID}',function(Request $request){



        ############## INFO ##############

        $fileID   = $request->fileID;
        
        // Cacche File
        Run::cacheFile($fileID);
        
        // Get File Name
        $file = Cache::get('file');

        //Get Fronend Name
        $fileInfo = Cache::get('file_info');

        // Get Page Count
        $pageCount = Run::getPageCount($file);



        ############# PROMISES #############

        ## A Break PDF into pages ##
        array_push($promises, dispatch((new A_BreakPDF($fileID))));

        ## B Convert Into Text ##
        $promises = [];
        for($page=1;$page<=$pageCount;$page++){

          //push to queue
          array_push($promises,dispatch((new B_ConvertToText($fileID, $page))));

        }

        ## C Parse Text ##
        array_push($promises, dispatch((new C_ParseText($fileID))));
        
        ## D Clean Up
        array_push($promises, dispatch((new D_CleanUp($fileID))));
        
        ## E Email ##
        array_push($promises, dispatch((new E_Notification($fileID))));



        ########### RUN PROMISES ###########

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

      })->middleware('jwt.auth');


      Route::get('/email/{fileID}',function(Request $request){
          $fileID = $request->fileID;

          $E = new E_Email($fileID);
          $E->handle();
      })->middleware('jwt.auth');


      Route::post('/delete/{fileID}',function(Request $request){

          $fileID = $request->fileID;

          $D       = new \App\Http\Controllers\Api\DownloadController($fileID);
          $D->delete();
      })->middleware('jwt.auth');

});