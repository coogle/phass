<?php

namespace Phass\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface;

class Glass extends AbstractPlugin
{
    protected function getEvent()
    {
        $controller = $this->getController();
        
        if(!$controller instanceof InjectApplicationEventInterface) {
            throw new \DomainException("Requires a controller that implements InjectApplicationEventInterface");
        }
        
        $event = $controller->getEvent();
        
        if(!$event instanceof MvcEvent) {
            $params = $event->getParams();
            $event = new MvcEvent();
            $event->setParams($params);
        }
        
        return $event;
    }
    
    public function sendResponse(ResponseInterface $response)
    {
        $event = $this->getEvent();
        $event->setResponse($response);
        $event->getApplication()->getEventManager()->trigger(MvcEvent::EVENT_FINISH, $event);
        exit;
    }
}