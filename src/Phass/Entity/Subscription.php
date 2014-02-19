<?php

namespace Phass\Entity;

use Zend\Uri\UriFactory;
use Zend\Uri\Uri;

class Subscription extends GlassModelAbstract
{
    const UPDATE = "UPDATE";
    const DELETE = "DELETE";
    const INSERT = "INSERT";
    
    /**
     * @var string
     */
    protected $_kind;
    
    /**
     * @var string
     */
    protected $_id;
    
    /**
     * @var \DateTime
     */
    protected $_updated;
    
    /**
     * @var string
     */
    protected $_collection;
    
    /**
     * @var \Zend\Uri\Uri
     */
    protected $_callbackUrl;
    
    /**
     * @var string
     */
    protected $_verifyToken;
    
    /**
     * @var string
     */
    protected $_userToken;
    
    /**
     * @var \ArrayObject
     */
    protected $_operations;
    
    public function __construct()
    {
        $this->_operations = new \ArrayObject();
    }
    
    /**
	 * @return the $_operations
	 */
	public function getOperations() {
		return $this->_operations;
	}

	/**
	 * @param ArrayObject $_operations
	 * @return self
	 */
	public function setOperations(array $_operations) {
		$this->_operations = new \ArrayObject($_operations);
		return $this;
	}

	/**
     * @return the $_kind
     */
    public function getKind() {
        return $this->_kind;
    }

    /**
     * @return the $_id
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * @return the $_updated
     */
    public function getUpdated() {
        return $this->_updated;
    }

    /**
     * @return the $_collection
     */
    public function getCollection() {
        return $this->_collection;
    }

    /**
     * @return the $_callbackUrl
     */
    public function getCallbackUrl() {
        return $this->_callbackUrl;
    }

    /**
     * @return the $_verifyToken
     */
    public function getVerifyToken() {
        return $this->_verifyToken;
    }

    /**
     * @return the $_userToken
     */
    public function getUserToken() {
        return $this->_userToken;
    }

    /**
     * @param string $_kind
     * @return self
     */
    public function setKind($_kind) {
        $this->_kind = $_kind;
        return $this;
    }

    /**
     * @param string $_id
     * @return self
     */
    public function setId($_id) {
        
        $_id = strtolower($_id);
        switch(true) {
        	case $_id == 'timeline':
        	case $_id == 'locations':
        	    break;
        	default:
        	    throw new \InvalidArgumentException("Invalid Subscription ID");
        }
        
        $this->_id = $_id;
        return $this;
    }

    /**
     * @param DateTime $_updated
     * @return self
     */
    public function setUpdated($_updated) {
        $this->_updated = $this->convertToDateTime($_updated);
        return $this;
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
     * @param \Zend\Uri\Uri $_callbackUrl
     * @return self
     */
    public function setCallbackUrl($_callbackUrl) {
        
        switch(true) {
        	case is_string($_callbackUrl):
        	    $_callbackUrl = UriFactory::factory($_callbackUrl);
        	    break;
        	case $_callbackUrl instanceof Uri:
        	    break;
        	default:
        	    throw new \InvalidArgumentException("Callback must be string or instance of Zend\Uri\Uri");
        }
        
        $this->_callbackUrl = $_callbackUrl;
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

    /**
     * @param string $_userToken
     * @return self
     */
    public function setUserToken($_userToken) {
        $this->_userToken = $_userToken;
        return $this;
    }

    public function fromJsonResult(array $result)
    {
        $this->setCallbackUrl(isset($result['callbackUrl']) ? $result['callbackUrl'] : null)
             ->setCollection(isset($result['collection']) ? $result['collection'] : null)
             ->setId(isset($result['id']) ? $result['id'] : null)
             ->setKind(isset($result['kind']) ? $result['kind'] : null)
             ->setUpdated(isset($result['updated']) ? $result['updated'] : null)
             ->setUserToken(isset($result['userToken']) ? $result['userToken'] : null)
             ->setVerifyToken(isset($result['verifyToken']) ? $result['verifyToken'] : null);
        
        return $this;
    }
    
    public function toArray()
    {
        if($updated = $this->getUpdated()) {
            $updated = $updated->format(\DateTime::RFC3339);
        } else {
            $updated = null;
        }
        
        $retval = array(
            'callbackUrl' => $this->getCallbackUrl()->__toString(),
            'collection' => $this->getCollection(),
            'id' => $this->getId(),
            'kind' => $this->getKind(),
            'updated' => $updated,
            'userToken' => $this->getUserToken(),
            'verifyToken' => $this->getVerifyToken(),
            'operation' => $this->getOperations()->getArrayCopy()
        );
        
        return $retval;
    }
}