<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: yandex/cloud/compute/v1/instance_service.proto

namespace App\Rest\Google\Instance;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Google\Cloud\Compute\V1\InstancesClient;
use Google\Cloud\Compute\V1\AttachedDisk;
use Google\Cloud\Compute\V1\AttachedDiskInitializeParams;
use Google\Cloud\Compute\V1\Instance;
use Google\Cloud\Compute\V1\NetworkInterface;
use Google\Cloud\Compute\V1\Operation;
use Google\Cloud\Compute\V1\ZoneOperationsClient;
use Google\Cloud\Compute\V1\AccessConfig;
use Google\Cloud\Compute\V1\Scheduling;
use Google\ApiCore\ApiException;

class GetInstance 
{
	private $projectId;
	private $keypath;
	private $name;
	
	public function __construct($name=null,$projectId=null,$keypath=null) {
		$this->projectId=$projectId;
		$this->keypath=$keypath;
		$this->name=$name;
		
    }

	public function execute($retry=3)
	{
		sleep(1);
		if($retry<0)return FALSE;
		if($retry<3)sleep(3-$retry);
		$result=array();
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.$this->keypath);
		try
		{
			$instancesClient = new InstancesClient();
			$allInstances = $instancesClient->aggregatedList($this->projectId);
			foreach ($allInstances as $zone => $zoneInstances) {
				$instances = $zoneInstances->getInstances();
				if (count($instances) > 0) {
					
					foreach ($instances as $instance) {
						
						if($instance->getName()==$this->name)

						{
							return 
							array(
								'name'=>$instance->getName(),
								'id'=>$instance->getId(),
								'ip'=>$instance->getNetworkInterfaces()[0]->getAccessConfigs()[0]->getNatIp(),
								'status'=>$instance->getStatus()							
							);
						}

					}
				}
			}
		}
		catch(ApiException $exception)
		{
			return $this->execute($retry-1);
		}
		return FALSE;

		}
}

