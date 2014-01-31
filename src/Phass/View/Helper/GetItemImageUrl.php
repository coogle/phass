<?php

namespace Phass\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\FactoryInterface;
use Phass\Entity\Timeline\Item;
use Phass\Entity\Timeline\Attachment;
use Zend\EventManager\EventManagerAwareInterface;
use Phass\Api\Exception\ApiCallException;
use Zend\Log\Logger;

class GetItemImageUrl extends AbstractHelper implements ServiceLocatorAwareInterface, FactoryInterface, EventManagerAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    use \Phass\Entity\SimpleFactoryTrait;
    use \Zend\EventManager\EventManagerAwareTrait;
    
    public function __invoke($item, $idOrIndex = 0)
    {
        if(!$item instanceof Item)
        {
            throw new \InvalidArgumentException("Must provide a glass item object");
        }
        
        if(count($item->getAttachments()) <= 0) {
            return '';
        }
        
        $api = $this->getServiceLocator()->getServiceLocator()->get('Phass\Api\Client');
        
        $attachmentId = "";
        
        if(ctype_digit((string)$idOrIndex)) {
            
            $this->getEventManager()->trigger('log', null, array('message' => "Using Index for Attachement"));
            if($idOrIndex >= count($item->getAttachments())) {
                throw new \RuntimeException("Requested attachment index is out of bounds for item");
            }
            
            $attachments = $item->getAttachments();
            
            $attachmentId = $attachments[$idOrIndex]->getId();
        } elseif($idOrIndex instanceof Attachment) {
            $attachmentId = $idOrIndex->getId();
        } else {
            $this->getEventManager()->trigger('log', null, array('message' => "Using ID for Attachment"));
            $attachmentId = $idOrIndex;
        }
        
        try {
            $attachment = $api->execute('timeline::attachment::get', array('itemId' => $item->getId(), 'attachmentId' => $attachmentId));
        } catch(ApiCallException $e) {
            $this->getEventManager()->trigger('log', null, array('message' => "Exception Retrieving Attachment URL: {$e->getMessage()}", 'priority' => Logger::ERR));
            return '';
        }
        
        return $attachment->getContentUrl();
    }
}