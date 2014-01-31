<?php

namespace Phass\Api\Timeline;

use Phass\Api\ApiAbstract;
use Phass\Entity\Timeline\Item;
use Zend\Http\Request;

class Insert extends ApiAbstract
{
    public function execute($data = null)
    {
        if(!$data instanceof Item) {
            throw new \InvalidArgumentException("Must provide a Timeline Item");
        }
        
        if(is_null($data->getHtml()) && !is_null($data->getTemplate())) {
            $data->render();
        }
        
        $itemAttachments = $data->getAttachments();
        
        if($itemAttachments->count() > 0) {
            return $this->executeWithAttachments($data);
        }
        
        $client = $this->getHttpClient('/mirror/v1/timeline', Request::METHOD_POST);
        
        $rawPost = $data->toJson(false);
        
        $client->setRawBody($rawPost);
        
        $response = $this->executeRequest($client);
        
        $retval = $this->getServiceLocator()->get('Phass\Timeline\Item');
        $retval->fromJsonResult($response);
        
        return $retval;
    }
    
    protected function executeWithAttachments(Item $item)
    {
        $item = clone $item;
        $itemAttachments = $item->getAttachments();
        
        $item->setAttachments(array());
        
        $client = $this->getHttpClient('/upload/mirror/v1/timeline', Request::METHOD_POST);
        
        $boundary = md5($this->getGlassService()->generateGuid());
        
        $client->getRequest()
               ->getHeaders()
               ->addHeaders(array(
                    'Content-Type' => 'multipart/related; boundary="' . $boundary . '"',
                ));
               
        $content = "--$boundary\nContent-Type: application/json; charset=UTF-8\n\n";
        $content .= $item->toJson(false);
        
        foreach($itemAttachments as $attachment) {
            $content .= "\n--$boundary\nContent-Type: {$attachment->getMimeType()}\nContent-Transfer-Encoding: binary\n\n{$attachment->getContent()}";
        }
        
        $content .= "\n--$boundary--";
        
        $client->setRawBody($content);
        
        $response = $this->executeRequest($client);
        
        $retval = $this->getServiceLocator()->get('Phass\Timeline\Item');
        $retval->fromJsonResult($response);
        
        return $retval;
    }
}