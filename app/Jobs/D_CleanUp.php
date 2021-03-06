<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Http\Controllers\JobControllers\CleanUp;
class D_CleanUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $fileID)
    {
        $this->fileID = $fileID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $C = new CleanUp($this->fileID);
        $C->handle();
    }
}
