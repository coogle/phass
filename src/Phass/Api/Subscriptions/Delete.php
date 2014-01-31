<?php

namespace Phass\Api\Subscriptions;

use Phass\Api\ApiAbstract;
use Zend\Http\Request;

class Delete extends ApiAbstract
{
    public function execute($data = null)
    {
        $client = $this->getHttpClient('/mirror/v1/subscriptions/' . (string)$data, Request::METHOD_DELETE);
        $response = $this->executeRequest($client);
        return;
    }
}