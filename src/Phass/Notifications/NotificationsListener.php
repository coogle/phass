<?php

namespace Phass\Notifications;

use Phass\PhassEvents as GlassEvent;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\MvcEvent;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Phass\Entity\Subscription\Notification\AbstractNotification;
use Zend\EventManager\EventManagerInterface;
use Phass\Service\GlassService;
use Zend\Log\Logger;

class NotificationsListener implements SharedListenerAggregateInterface, EventManagerAwareInterface, ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    use \Phass\Log\LoggerTrait;
    
    protected $_listeners = array();
    
    /**
     * @var EventManagerInterface
     */
    protected $_eventManager;
    
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(GlassService::EVENT_IDENTIFIER);
        $this->_eventManager = $eventManager;
        return $this;
    }
    
    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->_eventManager;
    }
    
    public function attachShared(SharedEventManagerInterface $events)
    {
        $self = $this;
        $attachEvent = function($glassEvent, $method) use ($events, $self)
        {
            $this->_listeners[] = $events->attach(GlassService::EVENT_IDENTIFIER, $glassEvent, array($self, $method), -100);
        };
        
        $attachEvent(GlassEvent::EVENT_SUBSCRIPTION_CUSTOM, 'onSubscriptionEvent');
        $attachEvent(GlassEvent::EVENT_SUBSCRIPTION_DELETE, 'onSubscriptionEvent');
        $attachEvent(GlassEvent::EVENT_SUBSCRIPTION_LAUNCH, 'onSubscriptionEvent');
        $attachEvent(GlassEvent::EVENT_SUBSCRIPTION_LOCATION, 'onSubscriptionEvent');
        $attachEvent(GlassEvent::EVENT_SUBSCRIPTION_REPLY, 'onSubscriptionEvent');
        $attachEvent(GlassEvent::EVENT_SUBSCRIPTION_SHARE, 'onSubscriptionEvent');
    }
    
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach($this->_listeners as $key => $val)
        {
            if($events->detach($val)) {
                unset($this->_listeners[$key]);
            }
        }
    }
    
    public function onSubscriptionEvent(Event $e)
    {
        $this->logEvent("Got Subscription Notification!");
        
        $config = $this->getServiceLocator()->get('Config');
        
        if(is_null($config['phass']['subscriptionController'])) {
        	$this->logEvent("Subscription Controller Not Specified in Config", Logger::ERR);
            throw new \RuntimeException("Subscription Controller Not Specified in Config");
        }
        
        $application = $this->getServiceLocator()->get('Application');
        $eventManager = $application->getEventManager();
        $notification = $e->getParam('notification', null);
        
        if(is_null($notification) || !($notification instanceof AbstractNotification)) {
        	$this->logEvent("Failed to retrieve notification object", Logger::ERR);
            throw new \RuntimeException("Failed to rertieve notification object");
        }
        
        $this->logEvent("Triggering EVENT_SUBSCRIPTION_RESOLVE_USER", Logger::DEBUG);
        
        $result = $this->getEventManager()->trigger(
            GlassEvent::EVENT_SUBSCRIPTION_RESOLVE_USER, 
            null, 
            array(
                'userToken' => $notification->getUserToken(),
                'tokenType' => ($e->getName() == GlassEvent::EVENT_SUBSCRIPTION_LOCATION) ? GlassService::COLLECTION_LOCATIONS : GlassService::COLLECTION_TIMELINE
            )
        );
        
        
        $OAuth2Token = $result->last();
        
        if(!$OAuth2Token instanceof \Phass\Entity\OAuth2\Token) {
            $this->logEvent("Warning, will not trigger subscription events, as the EVENT_SUBSCRIPTION_RESOLVE_USER event did not return a valid OAuth2 token for this user", Logger::WARN);
            return;
        }
        
        $tokenStorageObj = $this->getServiceLocator()->get('OAuth2\TokenStore');
        $tokenStorageObj->store($OAuth2Token);
        
        $this->logEvent("Dispatching to Subscription Controller", Logger::DEBUG);
        
        $mvcEvent = new MvcEvent();
        $mvcEvent->setTarget($application)
                 ->setParam('notification', $notification)
                 ->setApplication($application)
                 ->setRequest(new Request())
                 ->setResponse(new Response())
                 ->setRouter($this->getServiceLocator()->get('Router'));
        
        switch($e->getName()) {
            case GlassEvent::EVENT_SUBSCRIPTION_CUSTOM:
                $action = 'onCustom';
                break;
            case GlassEvent::EVENT_SUBSCRIPTION_DELETE:
                $action = 'onDelete';
                break;
            case GlassEvent::EVENT_SUBSCRIPTION_LAUNCH:
                $action = 'onLaunch';
                break;
            case GlassEvent::EVENT_SUBSCRIPTION_LOCATION:
                $action = "onLocation";
                break;
            case GlassEvent::EVENT_SUBSCRIPTION_REPLY:
                $action = "onReply";
                break;
            case GlassEvent::EVENT_SUBSCRIPTION_SHARE:
                $action = "onShare";
                break;
            default:
                throw new \RuntimeException("Unknown Event");
        }
        
        $matches = new RouteMatch(array(
            'controller' => $config['phass']['subscriptionController'],
            'action' => $action
        ));

        $mvcEvent->setRouteMatch($matches);
                
        $result = $eventManager->trigger(MvcEvent::EVENT_DISPATCH, $mvcEvent);
        
        /**
         * @todo Something useful with $response
         */
    }
}
