<?php

namespace Phass\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\Params as ZendParams;
use Zend\Json\Json;

class Params extends ZendParams
{
    public function fromJson()
    {
        $body = $this->getController()->getRequest()->getContent();
        
        if(!empty($body)) {
            $json = Json::decode($body, Json::TYPE_ARRAY);
            
            if(!empty($json)) {
                return $json;
            }
        }
        
        return false;
    }
}