<?php

namespace Phass\Api\Subscriptions;

use Phass\Api\ApiAbstract;
use Zend\Http\Request;
use Phass\Entity\Subscription;

class ListApi extends ApiAbstract
{
    public function execute($data = null)
    {
        $client = $this->getHttpClient('/mirror/v1/subscriptions', Request::METHOD_GET);
        
        $response = $this->executeRequest($client);
        
        $retval = array();
        
        foreach($response['items'] as $subscriptionItem)
        {
            $subscriptionObj = new Subscription();
            $subscriptionObj->fromJsonResult($subscriptionItem);
            
            $retval[] = $subscriptionObj;
        }
        
        return new \ArrayObject($retval);
    }
}