<?php

namespace Phass\Entity\Subscription\Notification;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Phass\Service\GlassService;

abstract class AbstractNotification implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    
    /**
     * @var string
     */
    protected $_collection;
    
    /**
     * @var string
     */
    protected $_operation;
    
    /**
     * @var string
     */
    protected $_userToken;
    
    /**
     * @var string
     */
    protected $_verifyToken;
    
    static public function getInstanceFromArray(array $input, \Zend\ServiceManager\ServiceLocatorInterface $sl, $action = null)
    {
        if(!isset($input['collection'])) {
            throw new \InvalidArgumentException("Invalid Input expected 'collection'");
        }
        
        $obj = null;
        switch($input['collection']) {
        	case GlassService::COLLECTION_TIMELINE:
        	    
        	    switch($action) {
        	    	case GlassService::ACTION_TYPE_SHARE:
        	    	    $obj = new Share();
        	    	    break;
        	    	case GlassService::ACTION_TYPE_REPLY:
        	    	    $obj = new Reply();
        	    	    break;
        	    	case GlassService::ACTION_TYPE_LAUNCH:
        	    	    $obj = new Launch();
        	    	    break;
        	    	case GlassService::ACTION_TYPE_DELETE:
        	    	    $obj = new Delete();
        	    	    break;
        	    	case GlassService::ACTION_TYPE_CUSTOM:
        	    	    $obj = new CustomMenuItem();
        	    	    break;
        	    	default:
        	    	    throw new \InvalidArgumentException("You must provide an action if a timeline item");
        	    }
        	    
        	    $obj->setCollection(GlassService::COLLECTION_TIMELINE);
        	    
        	    break;
        	case GlassService::COLLECTION_LOCATIONS:
        	    $obj = new Location();
        	    $obj->setCollection(GlassService::COLLECTION_LOCATIONS);
        	    break;
        	default:
        	    throw new \InvalidArgumentException("Bad Collection");
        }
        
        $obj->setItemId($input['itemId'])
            ->setOperation($input['operation'])
            ->setUserToken($input['userToken'])
            ->setVerifyToken($input['verifyToken'])
            ->setServiceLocator($sl);
        
        return $obj;
    }
	/**
	 * @return the $_collection
	 */
	public function getCollection() {
		return $this->_collection;
	}

	/**
	 * @return the $_operation
	 */
	public function getOperation() {
		return $this->_operation;
	}

	/**
	 * @return the $_userToken
	 */
	public function getUserToken() {
		return $this->_userToken;
	}

	/**
	 * @return the $_verifyToken
	 */
	public function getVerifyToken() {
		return $this->_verifyToken;
	}

	/**
	 * @param string $_collection
	 * @return self
	 */
	public function setCollection($_collection) {
		$this->_collection = $_collection;
		return $this;
	}

	/**
	 * @param string $_operation
	 * @return self
	 */
	public function setOperation($_operation) {
		$this->_operation = $_operation;
		return $this;
	}

	/**
	 * @param string $_userToken
	 * @return self
	 */
	public function setUserToken($_userToken) {
		$this->_userToken = $_userToken;
		return $this;
	}

	/**
	 * @param string $_verifyToken
	 * @return self
	 */
	public function setVerifyToken($_verifyToken) {
		$this->_verifyToken = $_verifyToken;
		return $this;
	}

}