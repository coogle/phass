<?php

namespace Phass\Entity;

class Location extends GlassModelAbstract
{
    
    /**
     * @var string
     */
    protected $_kind;
    
    /**
     * @var double
     */
    protected $_accuracy;
    
    /**
     * @var string
     */
    protected $_address;
    
    /**
     * @var string
     */
    protected $_displayName;
    
    /**
     * @var string
     */
    protected $_id;
    
    /**
     * @var float
     */
    protected $_latitude;
    
    /**
     * @var float
     */
    protected $_longitude;
    
    /**
     * @var \DateTime
     */
    protected $_recordedTime;
    
    /**
	 * @return the $_kind
	 */
	public function getKind() {
		return $this->_kind;
	}

	/**
	 * @param string $_kind
	 * @return self
	 */
	public function setKind($_kind) {
		$this->_kind = $_kind;
		return $this;
	}

	public function toArray()
	{
	    $retval = array(
	       'kind' => $this->getKind(),
	       'timestamp' => $this->getRecordedTime(),
	       'latitude' => $this->getLatitude(),
	       'longitude' => $this->getLongitude(),
	       'accuracy' => $this->getAccuracy()
	    );
	    
	    return $retval;
	}
	
	public function fromJsonResult(array $result)
    {
        $this->setKind($result['kind'])
             ->setRecordedTime($this->convertToDateTime($result['timestamp']))
             ->setLatitude($result['latitude'])
             ->setLongitude($result['longitude'])
             ->setAccuracy($result['accuracy']);
        
        return $this;
    }
    
	/**
	 * @return the $_accuracy
	 */
	public function getAccuracy() {
		return $this->_accuracy;
	}

	/**
	 * @return the $_address
	 */
	public function getAddress() {
		return $this->_address;
	}

	/**
	 * @return the $_displayName
	 */
	public function getDisplayName() {
		return $this->_displayName;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_latitude
	 */
	public function getLatitude() {
		return $this->_latitude;
	}

	/**
	 * @return the $_longitude
	 */
	public function getLongitude() {
		return $this->_longitude;
	}

	/**
	 * @return the $_recordedTime
	 */
	public function getRecordedTime() {
		return $this->_recordedTime;
	}

	/**
	 * @param number $_accuracy
	 * @return self
	 */
	public function setAccuracy($_accuracy) {
		$this->_accuracy = $_accuracy;
		return $this;
	}

	/**
	 * @param string $_address
	 * @return self
	 */
	public function setAddress($_address) {
		$this->_address = $_address;
		return $this;
	}

	/**
	 * @param string $_displayName
	 * @return self
	 */
	public function setDisplayName($_displayName) {
		$this->_displayName = $_displayName;
		return $this;
	}

	/**
	 * @param string $_id
	 * @return self
	 */
	public function setId($_id) {
		$this->_id = $_id;
		return $this;
	}

	/**
	 * @param number $_latitude
	 * @return self
	 */
	public function setLatitude($_latitude) {
		$this->_latitude = $_latitude;
		return $this;
	}

	/**
	 * @param number $_longitude
	 * @return self
	 */
	public function setLongitude($_longitude) {
		$this->_longitude = $_longitude;
		return $this;
	}

	/**
	 * @param DateTime $_recordedTime
	 * @return self
	 */
	public function setRecordedTime($_recordedTime) {
		$this->_recordedTime = $_recordedTime;
		return $this;
	}

}