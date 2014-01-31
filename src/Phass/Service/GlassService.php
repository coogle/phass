<?php

namespace Phass\Service;

use Phass\Entity\Location;

class GlassService implements \Zend\ServiceManager\ServiceLocatorAwareInterface, \Zend\EventManager\EventManagerAwareInterface
{
    use \Phass\Log\LoggerTrait;
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    const COLLECTION_TIMELINE = "timeline";
    const COLLECTION_LOCATIONS = "locations";
    
    const ACTION_TYPE_SHARE = "SHARE";
    const ACTION_TYPE_REPLY = "REPLY";
    const ACTION_TYPE_DELETE = "DELETE";
    const ACTION_TYPE_CUSTOM = "CUSTOM";
    const ACTION_TYPE_LAUNCH = "LAUNCH";
     
    const EVENT_IDENTIFIER = 'Phass\Service\GlassService';
    
    const DEV_HTTPS_PROXY_URL = "https://mirrornotifications.appspot.com/forward";
    
    /**
     * @var \Zend\EventManager\EventManagerInterface
     */
    protected $_eventManager;
    
    /**
     * @var \Phass\Api\Client
     */
    protected $_glassApiClient;

    /**
     * @return \Zend\EventManager\EventManagerInterface
     */
    public function getEventManager() {
        return $this->_eventManager;
    }

    public function execute($api, $data = null)
    {
        return $this->getGlassApiClient()->execute($api, $data);
    }
    
    /**
     * @param \Zend\EventManager\EventManagerInterface $_eventManager
     * @return self
     */
    public function setEventManager(\Zend\EventManager\EventManagerInterface $_eventManager) {
        $this->_eventManager = $_eventManager;
        $this->_eventManager->addIdentifiers('Phass\Service\GlassService');
        return $this;
    }

    /**
     * @return the $_glassApiClient
     */
    public function getGlassApiClient() {
        return $this->_glassApiClient;
    }

    /**
     * @param \Phass\Api\Client $_glassApiClient
     * @return self
     */
    public function setGlassApiClient($_glassApiClient) {
        $this->_glassApiClient = $_glassApiClient;
        return $this;
    }

    static public function generateGuid()
    {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
    
    public function unsubscribe($collection)
    {
        switch(true) {
            case static::COLLECTION_LOCATIONS:
            case static::COLLECTION_TIMELINE:
                break;
            default:
                throw new \InvalidArgumentException("Invalid collection Type");
        }
        
        return $this->execute('subscriptions::delete', $collection);
    }
    
    public function getAllLocations()
    {
        $locations = $this->getMirrorService()->locations->listLocations();
        
        $retval = array();
        
        foreach($locations as $key => $val) {
            if($val instanceof \Google_Service_Mirror_Location) {
                $retval[] = Location::getInstanceFromApiResource($val);
            }
        }
        
        return $retval;
    }
    
    public function getLocation($id = null)
    {
        switch(true) {
            case is_null($id):
                $itemId = "latest";
                break;
            case ($id instanceof \Phass\Entity\Subscription\Notification\Location):
                $itemId = $id->getItemId();
                break;
            case is_string($id):
                $itemId = $id;
                break;
            default:
                throw new \InvalidArgumentException("Invalid Location ID");
        }
        
        $location = $this->getMirrorService()->locations->get($itemId);
        
        if($location instanceof \Google_Service_Mirror_Location) {
            return Location::getInstanceFromApiResource($location);
        }
        
        return null;
    }
    
    public function subscribe($collection, $operations = array(), $guid = null)
    {
        switch(true) {
            case $collection == static::COLLECTION_LOCATIONS:
            case $collection == static::COLLECTION_TIMELINE:
                break;
            default:
                throw new \InvalidArgumentException("Invalid collection Type");
        }
        
        if(is_null($guid)) {
            $guid = static::generateGuid();
        }
        
        $config = $this->getServiceLocator()->get('Config');
        
        if(is_null($config['googleglass']['subscriptionUri'])) {
            $router = $this->getServiceLocator()->get('Router');
            $requestUri = $router->getRequestUri();
            $requestUri->setQuery(null);
            if(isset($config['googleglass']['development']) && $config['googleglass']['development']) {
                $requestUri->setScheme('http');
            } else {
                $requestUri->setScheme('https');
            }
            
            $callbackUrl = $router->assemble(array(), array('name' => 'googleglass-subscription-callback', 'force_canonical' => true, 'uri' => $requestUri));
            
        } else {
            $callbackUrl = $config['googleglass']['subscriptionUri'];
        }
        
        if(isset($config['googleglass']['development']) && $config['googleglass']['development']) {
            $callbackUrl = static::DEV_HTTPS_PROXY_URL . "?url=" . $callbackUrl;
        }
        
        if(empty($callbackUrl)) {
            throw new \Exception("Failed to build callback URL for subscription");
        }
        
        foreach($operations as $key => $val) {
            $val = strtoupper($val);
            
            switch($val) {
                case static::ACTION_TYPE_CUSTOM:
                case static::ACTION_TYPE_DELETE:
                case static::ACTION_TYPE_LAUNCH:
                case static::ACTION_TYPE_REPLY:
                case static::ACTION_TYPE_SHARE:
                    break;
                default:
               throw new \InvalidArgumentException("Action type '$val' not valid for subscription");
            }
            
            $operations[$key] = $val;
        }
        
        $operations = array_unique($operations);
        
        $subscription = $this->getServiceLocator()->get('Phass\Subscription');
        
        $subscription->setCollection($collection)
                     ->setUserToken($guid)
                     ->setCallbackUrl($callbackUrl)
                     ->setVerifyToken($this->generateVerifyToken())
                     ->setKind('mirror#subscription')
                     ->setOperations($operations);
        
        $this->execute('subscriptions::insert', $subscription);
        
        return $guid;
    }
    
    public function generateVerifyToken()
    {
        $config = $this->getServiceLocator()->get('Config');
        
        return sha1($config['googleglass']['randomKey']);
    }
    /**
     * @param unknown $userId
     * @return boolean
     */
    public function isExistingUser($userId)
    {
        $model = $this->getServiceLocator()->get('Phass\Model\CredentialsTable');
        $result = $model->findByUserId($userId);
        
        return !is_null($result);
    }
    
    /**
     * Returns true if the user is authenticated via OAuth
     * 
     * @return boolean
     */
    public function isAuthenticated() {
        
        return $this->getServiceLocator()->get('Phass\OAuth2\Token')->isValid();
    }    
    
}