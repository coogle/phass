<?php

namespace Phass\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractSubscriptionController extends AbstractActionController
{
    public function getNotificationTimelineItem()
    {
        $notification = $this->getEvent()->getParam('notification', null);
        
        if(!$notification instanceof \Phass\Entity\Subscription\Notification\TimelineItemGetterInterface)
        {
            
            throw new \InvalidArgumentException("Notification was not of proper type");
        }
        
        return $notification->getItem();
    }
    
    public function onDeleteAction()
    {
        
    }
    
    public function onShareAction()
    {
        
    }
    
    public function onCustomAction()
    {
        
    }
    
    public function onLocationAction()
    {
        
    }
    
    public function onLaunchAction()
    {
        
    }
    
    public function onReplyAction()
    {
        
    }
}