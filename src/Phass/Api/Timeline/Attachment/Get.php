<?php

namespace Phass\Api\Timeline\Attachment;

use Phass\Api\ApiAbstract;
use Zend\Http\Request;
use Zend\Log\Logger;

class Get extends ApiAbstract
{
    public function execute($data = null)
    {
        if(!isset($data['itemId']) || !isset($data['attachmentId']))
        {
            throw new \InvalidArgumentException("You must provide an itemId and an attachmentId");
        }
        
        $client = $this->getHttpClient("/mirror/v1/timeline/{$data['itemId']}/attachments/{$data['attachmentId']}", Request::METHOD_GET);
        
        $this->logEvent("Retrieving Attachment from URL: {$client->getUri()}", Logger::DEBUG);
        
        $response = $this->executeRequest($client);
        
        $attachment = $this->getServiceLocator()->get('Phass\Timeline\Attachment');
        
        $attachment->fromJsonResult($response);
        
        return $attachment;
    }
}