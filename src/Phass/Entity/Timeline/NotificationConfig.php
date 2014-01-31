<?php

namespace Phass\Entity\Timeline;

use Phass\Entity\GlassModelAbstract;

class NotificationConfig extends GlassModelAbstract
{
    const LEVEL_DEFAULT = "DEFAULT";
    
    /**
     * @var \DateTime
     */
    protected $_deliveryTime;
    
    /**
     * @var string
     */
    protected $_level;
	/**
	 * @return the $_deliveryTime
	 */
	public function getDeliveryTime() {
		return $this->_deliveryTime;
	}

	/**
	 * @return the $_level
	 */
	public function getLevel() {
		return $this->_level;
	}

	/**
	 * @param DateTime $_deliveryTime
	 * @return self
	 */
	public function setDeliveryTime($_deliveryTime) {
		$this->_deliveryTime = $_deliveryTime;
		return $this;
	}

	/**
	 * @param string $_level
	 * @return self
	 */
	public function setLevel($_level) {
		$this->_level = $_level;
		return $this;
	}
	
	public function fromJsonResult(array $result)
	{
	    $this->setLevel(isset($result['level']) ? $result['level'] : null)
	         ->setDeliveryTime(isset($result['deliveryTime']) ? $this->convertToDateTime($result['deliveryTime']) : null);
	    
	    return $this;
	}

	public function toArray()
	{
	    $retval = array('level' => $this->getLevel());
	    
	    $date = $this->getDeliveryTime();
	    
	    if($date instanceof \DateTime) {
	        $retval['deliveryTime'] = $date->format(\DateTime::RFC3339);
	    } else {
	        $retval['deliveryTime'] = null;
	    }
	    
	    return $retval;
	}
}