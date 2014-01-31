<?php

namespace Phass\Api\Timeline;

use Phass\Api\ApiAbstract;
use Zend\Http\Request;

class ListApi extends ApiAbstract
{
    public function execute($data = null) 
    {
        $client = $this->getHttpClient('/mirror/v1/timeline', Request::METHOD_GET);
        
        $response = $this->executeRequest($client);
        
        $timelineList = $this->getServiceLocator()->get('Phass\Timeline');
        $timelineList->fromJsonResult($response);
        
        return $timelineList;
    }
}
