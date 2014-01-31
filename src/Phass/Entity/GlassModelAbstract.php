<?php

namespace Phass\Entity;

use \Zend\ServiceManager\ServiceLocatorAwareInterface;
use \Zend\ServiceManager\FactoryInterface;
use Zend\Json\Json;

abstract class GlassModelAbstract implements ServiceLocatorAwareInterface, FactoryInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    use \Phass\Entity\SimpleFactoryTrait;
    
    /**
     * @var \Zend\Http\Client
     */
    protected $_httpClient;
    
	/**
	 * @return the $_httpClient
	 */
	public function getHttpClient() {
	    
	    if(is_null($this->_httpClient)) {
	        $this->_httpClient = $this->getServiceLocator()->get('Phass\Http\Client');
	    }
	    
		return $this->_httpClient;
	}

	/**
	 * @param \Zend\Http\Client $_httpClient
	 * @return self
	 */
	public function setHttpClient($_httpClient) {
		$this->_httpClient = $_httpClient;
		return $this;
	}

	protected function convertToDateTime($rfc3999)
	{
	    if(is_null($rfc3999))
	    {
	        return null;
	    }
	    
    	// We do this because the RFC constant wants it this way
    	$timeStamp = substr($rfc3999, 0, -5) . "+00:00";
    	return \DateTime::createFromFormat(\DateTime::RFC3339, $timeStamp);
	}
	
	public function toJson($includeEmptyKeys = true)
	{
	    $data = $this->toArray();
	    
	    if(!$includeEmptyKeys) {
	        foreach($data as $k => $v)
	        {
	            if(empty($v)) {
	                unset($data[$k]);
	            }
	        }
	    }
	    
	    return Json::encode($data);
	}
	
	abstract function fromJsonResult(array $result);
	abstract function toArray();
}