<?php

namespace Phass\Entity\Subscription\Notification;

trait GetItemTrait
{
    /**
     * @var string
     */
    protected $_itemId;
    
    public function setItemId($id) {
        $this->_itemId = $id;
        return $this;
    }
    
    public function getItemId() {
        return $this->_itemId;
    }
    
    /**
     * @return Google_Service_Mirror_TimelineItem
     */
    public function getItem()
    {
        $itemId = $this->getItemId();
        
        $glassService = $this->getServiceLocator()->get('Phass\Service\GlassService');
        return $glassService->execute('timeline::get', $itemId);
    }
}