<?php

namespace Phass\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Log\Logger;
use Phass\Events;
use Zend\Loader\Exception\SecurityException;
use Phass\Entity\Subscription\Notification\AbstractNotification;
use Phass\Service\GlassService;
use Zend\Uri\UriFactory;
use Zend\Json\Json;
use Phass\Entity\OAuth2\Token;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class CallbackController extends AbstractActionController
{
    use \Phass\Log\LoggerTrait;
    
    public function setEventManager(\Zend\EventManager\EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->addIdentifiers(GlassService::EVENT_IDENTIFIER);
        return $this;
    }
    
    public function subscriptionCallbackAction()
    {
        $this->logEvent("Recieved ping on Glass subscription endpoint");
        
        file_put_contents("/tmp/request.txt", var_export($this->getRequest()->__toString(), true));
        $glassService = $this->getServiceLocator()->get('Phass\Service\GlassService');
        
        $notification = $this->params()->fromJson();
        
        if($notification === false) {
            $this->logEvent("Invalid Subscription Notification", 'error');
            throw new \InvalidArgumentException("Invalid Subscription Notification");
        }
        
        if(!isset($notification['verifyToken'])) {
            $this->logEvent('Verify Token Not Present', 'error');
            throw new SecurityException("Verify Token not present");
        }
        
        if($notification['verifyToken'] !== $glassService->generateVerifyToken()) {
            $this->logEvent('Verify Token is invalid');
            throw new SecurityException("Verify Token is invalid");
        }
        
        switch($notification['collection']) {
            case GlassService::COLLECTION_LOCATIONS:
                $obj = AbstractNotification::getInstanceFromArray($notification, $this->getServiceLocator());
                
                $this->getEventManager()->trigger(Events::EVENT_SUBSCRIPTION_LOCATION, null, array('notification' => $obj));
                break;
            case GlassService::COLLECTION_TIMELINE:
                
                $this->logEvent("Received Timeline Subscription Notification");
                
                foreach($notification['userActions'] as $action) {
                    if(!isset($action['type'])) {
                        throw new \InvalidArgumentException("Missing Action Type");
                    }
                    
                    $obj = AbstractNotification::getInstanceFromArray($notification, $this->getServiceLocator(),  $action['type']);
                    
                    switch($action['type']) {
                        case GlassService::ACTION_TYPE_CUSTOM:
                            $event = Events::EVENT_SUBSCRIPTION_CUSTOM;
                            break;
                        case GlassService::ACTION_TYPE_DELETE:
                            $event = Events::EVENT_SUBSCRIPTION_DELETE;
                            break;
                        case GlassService::ACTION_TYPE_LAUNCH:
                            $event = Events::EVENT_SUBSCRIPTION_LAUNCH;
                            break;
                        case GlassService::ACTION_TYPE_REPLY:
                            $event = Events::EVENT_SUBSCRIPTION_REPLY;
                            break;
                        case GlassService::ACTION_TYPE_SHARE:
                            $event = Events::EVENT_SUBSCRIPTION_SHARE;
                            break;
                        default:
                            throw new \InvalidArgumentException("Invalid Action Type");
                    }
                    
                    $this->getEventManager()->trigger($event, null, array('notification' => $obj));
                }
                break;
            default:
                throw new \InvalidArgumentException("Invalid Collection Provided");
        }
        
        $response = $this->getResponse();
        $response->setStatusCode(\Zend\Http\Response::STATUS_CODE_200);
        $response->setContent(null);
        
        $this->glass()->sendResponse($response);
    }
    
}