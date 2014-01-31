<?php

namespace Phass\Api\Timeline;

use Phass\Api\ApiAbstract;
use Zend\Http\Request;

class Get extends ApiAbstract
{
    public function execute($data = null)
    {
        $client = $this->getHttpClient('/mirror/v1/timeline' . urlencode((string)$data), Request::METHOD_GET);
        
        $responseData = $this->executeRequest($client);
        
        $timelineItem = $this->getServiceLocator()->get('Phass\Timeline\Item');
        $timelineItem->fromJsonResult($responseData);
        
        return $timelineItem;
    }
}