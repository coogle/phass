<?php

namespace Phass\Api;

use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Phass\Api\Exception\ApiCallNotFoundException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Log\Logger;

class Client implements ServiceProviderInterface, FactoryInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    use \Phass\Entity\SimpleFactoryTrait;
    use \Phass\Log\LoggerTrait;
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'timeline::insert' => 'Phass\Api\Timeline\Insert',
                'timeline::get' => 'Phass\Api\Timeline\Get',
                'timeline::list' => 'Phass\Api\Timeline\ListApi',
                
                'timeline::attachment::get' => 'Phass\Api\Timeline\Attachment\Get',
                'timeline::attachment::list' => 'Phass\Api\Timeline\Attachment\ListApi',
                
                'subscriptions::list' => 'Phass\Api\Subscriptions\ListApi',
                'subscriptions::delete' => 'Phass\Api\Subscriptions\Delete',
                'subscriptions::insert' => 'Phass\Api\Subscriptions\Insert',
                
                'contacts::list' => 'Phass\Api\Contacts\ListApi',
                'contacts::delete' => 'Phass\Api\Contacts\Delete',
                'contacts::insert' => 'Phass\Api\Contacts\Insert'
            )
        );
    }
    
    public function execute($apiCall, $data = null)
    {
        try {
            $apiObject = $this->getServiceLocator()->get($apiCall);
            $apiObject->setServiceLocator($this->getServiceLocator());
        } catch(ServiceNotFoundException $e) {
            throw new ApiCallNotFoundException("The API Call '$apiCall' was not found");
        }
        
        if(!$apiObject instanceof ApiAbstract) {
            throw new ApiCallNotFoundException("The API Call is not valid");
        }
        
        try {
            $this->logEvent("Executing API call '$apiCall'", Logger::DEBUG);
            
            return $apiObject->execute($data);
        } catch(\Exception $e) {
            $this->logEvent("Exception caught trying to execute API call '$apiCall': {$e->getMessage()}", Logger::ERR);
            throw $e;
        }
    }
}