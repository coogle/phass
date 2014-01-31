<?php

namespace Phass\Api\Subscriptions;

use Phass\Api\ApiAbstract;
use Phass\Entity\Subscription;
use Zend\Http\Request;

class Insert extends ApiAbstract
{
    public function execute($data = null)
    {
        if(!$data instanceof Subscription)
        {
            throw new \InvalidArgumentException("Must provide a subscription entity");
        }
        
        $client = $this->getHttpClient('/mirror/v1/subscriptions', Request::METHOD_POST);
        
        $client->setRawBody($data->toJson());
        
        $response = $this->executeRequest($client);
        
        $retval = $this->getServiceLocator()->get('Phass\Subscription');
        $retval->fromJsonResult($response);
        
        return $retval;
    }
}