<?php

namespace Phass\Entity\Timeline;

use Phass\Entity\GlassModelAbstract;

class MenuItem extends GlassModelAbstract
{
    const REPLY = "REPLY";
    const READ_ALOUD = "READ_ALOUD";
    const SHARE = "SHARE";
    const REPLY_ALL = "REPLY_ALL";
    const DELETE = "DELETE";
    const VOICE_CALL = "VOICE_CALL";
    const NAVIGATE = "NAVIGATE";
    const TOGGLE_PINNED = "TOGGLE_PINNED";
    const OPEN_URI = "OPEN_URI";
    const PLAY_VIDEO = "PLAY_VIDEO";
    const CUSTOM = "CUSTOM";
    
    /**
     * @var string
     */
    protected $_action;
    
    /**
     * @var string
     */
    protected $_id;
    
    /**
     * @var string
     */
    protected $_payload;
    
    /**
     * @var bool
     */
    protected $_removeWhenSelected;
    
    /**
     * @var \ArrayObject
     */
    protected $_values;
    
    public function __construct($action = null) {
        
        if(!is_null($action)) {
            $this->setAction($action);
        }
        
    }

    public function toArray()
    {
        return array(
            'action' => $this->getAction(),
            'payload' => $this->getPayload()
        );
    }
    
    public function fromJsonResult(array $result)
    {
        $this->setAction($result['action']);
        return $this;
    }
    
	/**
	 * @return the $_action
	 */
	public function getAction() {
		return $this->_action;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_payload
	 */
	public function getPayload() {
		return $this->_payload;
	}

	/**
	 * @return the $_removeWhenSelected
	 */
	public function getRemoveWhenSelected() {
		return $this->_removeWhenSelected;
	}

	/**
	 * @return the $_values
	 */
	public function getValues() {
		return $this->_values;
	}

	/**
	 * @param string $_action
	 * @return self
	 */
	public function setAction($_action) {
		$this->_action = $_action;
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
	 * @param string $_payload
	 * @return self
	 */
	public function setPayload($_payload) {
		$this->_payload = $_payload;
		return $this;
	}

	/**
	 * @param boolean $_removeWhenSelected
	 * @return self
	 */
	public function setRemoveWhenSelected($_removeWhenSelected) {
		$this->_removeWhenSelected = $_removeWhenSelected;
		return $this;
	}

	/**
	 * @param ArrayObject $_values
	 * @return self
	 */
	public function setValues($_values) {
		$this->_values = $_values;
		return $this;
	}

    
}