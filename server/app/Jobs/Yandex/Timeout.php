<?php

namespace App\Jobs\Yandex;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Rest\Yandex\Instance\GetInstance;
use App\Rest\Yandex\Instance\CreateInstance;
use App\Rest\Yandex\Instance\DeleteInstance;
use App\Models\User;
use App\Models\UserCloudVm;
use App\Models\Cloud;
use App\Models\Iamtoken;
use Illuminate\Support\Str;
use App\Models\UserCloudVmLog;
class Timeout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	 public $timeout = 600;
	 protected $uservm;
	 protected $cloud;
	 private $response;

    public function __construct(UserCloudVm $uservm,Cloud $cloud)
    {
         $this->uservm=$uservm;
		 $this->cloud=$cloud;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$start=intval($this->uservm->progress);
		if($start<40)$start=40;
		if($start<100)
		{
			for($i=$start;$i<100;$i++)
			{
				sleep(1);
				
				$this->uservm->progress=$i;
				$this->uservm->save();
			}
		}
		else
		{
			for($i=0;$i<60;$i++)
			{
				sleep(1);
			}
		}
		$this->uservm->progress=100;
		$this->uservm->status="running";
		$this->uservm->save();		
			
			
			
			
    }

}
