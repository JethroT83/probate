<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
#use App\Http\Requests\LoginRequest;

############################ RUN ##########################
use App\Jobs\A_BreakPDF;
use App\Jobs\B_ConvertToText;
use App\Jobs\C_ParseText;
use App\Jobs\D_CleanUp;
use App\Jobs\E_Email;
use Carbon\Carbon;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\EachPromise;

use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;
use App\Http\Controllers\JobControllers\ConvertToText as cText;
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
        'uses' => 'UploadController@store'
      ]);

      Route::get('/download/{fileID}',function(Request $request){
        
        $fileID   = $request->fileID;
        $D        = new \App\Http\Controllers\Api\DownloadController($fileID);

        return    $D->handle();
      });

      Route::get('/files','UploadController@get');


      Route::get('/run/{fileID}',function(Request $request){

        $fileID   = $request->fileID;
        Run::cacheFile($fileID);
        $file = Cache::get('file');

        //Break PDF into pages
        $job1 = (new A_BreakPDF($fileID));

        $p1 = new Promise(dispatch($job1), 
                          function () 
                          use (&$called) { $called = true; });
        $p1->then(function () {

          // Get the Number of Pages
          $pageCount = Run::getPageCount($file);

          $promises = [];
          for($page=1;$page<=$pageCount;$page++){

            //push to queue
            array_push($promises,dispatch((new B_ConvertToText($fileID, $page))));

          }

          array_push($promises, dispatch((new C_ParseText($fileID))));
          array_push($promises, dispatch((new D_CleanUp($fileID))));
          //array_push($promises, dispatch((new E_Email($fileID))));


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


      Route::get('/email/{fileID}',function(Request $request){
          $fileID = $request->fileID;

          $E = new E_Email($fileID);
          $E->handle();
      });
});

Route::post('login', [ 'as' => 'login', 'uses' => 'AuthController@halt']);