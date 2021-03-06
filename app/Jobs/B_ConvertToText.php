<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Http\Controllers\JobControllers\ConvertToText as cText;
use App\Core\Services\RunService as Run;
use Illuminate\Support\Facades\Cache as Cache;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\EachPromise;
class B_ConvertToText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileID;
    protected $file;
    protected $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $fileID, int $page)
    {
        $this->fileID = $fileID;

        ### CACHE FILE ###
        Run::cacheFile($fileID);

        $this->file = Cache::get('file');

        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){

        $T = new cText($this->page);
        $T->handle();

    }
}
