<?php

namespace Phass\Entity;

class Contact extends GlassModelAbstract
{
    /**
     * @var string
     */
    protected $_kind;
    
    /**
     * @var string
     */
    protected $_source;
    
    /**
     * @var string
     */
    protected $_id;
    
    /**
     * @var string
     */
    protected $_displayName;
    
    /**
     * @var \ArrayObject
     */
    protected $_imageUrls;
    
    /**
     * @var string
     */
    protected $_type;
    
    /**
     * @var \ArrayObject
     */
    protected $_acceptTypes;
    
    /**
     * @var string
     */
    protected $_phoneNumber;
    
    /**
     * @var int
     */
    protected $_priority;
    
    /**
     * @var \ArrayObject
     */
    protected $_acceptCommands;
    
    /**
     * @var string
     */
    protected $_speakableName;
    
    /**
     * @var \ArrayObject
     */
    protected $_sharingFeatures;
    
    public function __construct()
    {
        $this->_imageUrls = new \ArrayObject();
        $this->_acceptTypes = new \ArrayObject();
        $this->_acceptCommands = new \ArrayObject();
        $this->_sharingFeatures = new \ArrayObject();
        
    }
    
    public function toArray()
    {
        return array(
            'kind' => $this->getKind(),
            'source' => $this->getSource(),
            'id' => $this->getId(),
            'displayName' => $this->getDisplayName(),
            'type' => $this->getType(),
            'phoneNumber' => $this->getPhoneNumber(),
            'priority' => $this->getPriority(),
            'speakableName' => $this->getSpeakableName(),
            'imageUrls' => $this->getImageUrls()->getArrayCopy(),
            'acceptTypes' => $this->getAcceptTypes()->getArrayCopy(),
            'acceptCommands' => $this->getAcceptCommands()->getArrayCopy(),
            'sharingFeatures' => $this->getSharingFeatures()->getArrayCopy()
        );
    }
    
    public function fromJsonResult(array $result)
    {
        $this->setKind(isset($result['kind']) ? $result['kind'] : null)
             ->setSource(isset($result['source']) ? $result['source'] : null)
             ->setId(isset($result['id']) ? $result['id'] : null)
             ->setDisplayName(isset($result['displayName']) ? $result['displayName'] : null)
             ->setType(isset($result['type']) ? $result['type'] : null)
             ->setPhoneNumber(isset($result['phoneNumber']) ? $result['phoneNumber'] : null)
             ->setPriority(isset($result['priority']) ? (int)$result['priority'] : null)
             ->setSpeakableName(isset($result['speakableName']) ? $result['speakableName'] : null);
        
        if(isset($result['imageUrls']) && is_array($result['imageUrls'])) {
            $this->setImageUrls($result['imageUrls']);
        }
        
        if(isset($result['acceptTypes']) && is_array($result['acceptTypes'])) {
            $this->setAcceptTypes($result['acceptTypes']);
        }
        
        if(isset($result['acceptCommands']) && is_array($result['acceptCommands'])) {
            $this->setAcceptCommands($result['acceptCommands']);
        }
        
        if(isset($result['sharingFeatures']) && is_array($result['sharingFeatures'])) {
            $this->setSharingFeatures($result['sharingFeatures']);
        }
        
        return $this;
    }
	/**
	 * @return the $_sharingFeatures
	 */
	public function getSharingFeatures() {
		return $this->_sharingFeatures;
	}

	/**
	 * @param ArrayObject $_sharingFeatures
	 * @return self
	 */
	public function setSharingFeatures(array $_sharingFeatures) {
		$this->_sharingFeatures = new \ArrayObject($_sharingFeatures);
		return $this;
	}

	/**
	 * @return the $_kind
	 */
	public function getKind() {
		return $this->_kind;
	}

	/**
	 * @return the $_source
	 */
	public function getSource() {
		return $this->_source;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_displayName
	 */
	public function getDisplayName() {
		return $this->_displayName;
	}

	/**
	 * @return the $_imageUrls
	 */
	public function getImageUrls() {
		return $this->_imageUrls;
	}

	/**
	 * @return the $_type
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @return the $_acceptTypes
	 */
	public function getAcceptTypes() {
		return $this->_acceptTypes;
	}

	/**
	 * @return the $_phoneNumber
	 */
	public function getPhoneNumber() {
		return $this->_phoneNumber;
	}

	/**
	 * @return the $_priority
	 */
	public function getPriority() {
		return $this->_priority;
	}

	/**
	 * @return the $_acceptCommands
	 */
	public function getAcceptCommands() {
		return $this->_acceptCommands;
	}

	/**
	 * @return the $_speakableName
	 */
	public function getSpeakableName() {
		return $this->_speakableName;
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
	 * @param string $_source
	 * @return self
	 */
	public function setSource($_source) {
		$this->_source = $_source;
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
	 * @param string $_displayName
	 * @return self
	 */
	public function setDisplayName($_displayName) {
		$this->_displayName = $_displayName;
		return $this;
	}

	/**
	 * @param ArrayObject $_imageUrls
	 * @return self
	 */
	public function setImageUrls(array $_imageUrls) {
		$this->_imageUrls = new \ArrayObject($_imageUrls);
		return $this;
	}

	/**
	 * @param string $_type
	 * @return self
	 */
	public function setType($_type) {
		$this->_type = $_type;
		return $this;
	}

	/**
	 * @param ArrayObject $_acceptTypes
	 * @return self
	 */
	public function setAcceptTypes(array $_acceptTypes) {
		$this->_acceptTypes = new \ArrayObject($_acceptTypes);
		return $this;
	}

	/**
	 * @param string $_phoneNumber
	 * @return self
	 */
	public function setPhoneNumber($_phoneNumber) {
		$this->_phoneNumber = $_phoneNumber;
		return $this;
	}

	/**
	 * @param number $_priority
	 * @return self
	 */
	public function setPriority($_priority) {
		$this->_priority = $_priority;
		return $this;
	}

	/**
	 * @param ArrayObject $_acceptCommands
	 * @return self
	 */
	public function setAcceptCommands(array $_acceptCommands) {
		$this->_acceptCommands = new \ArrayObject($_acceptCommands);
		return $this;
	}

	/**
	 * @param string $_speakableName
	 * @return self
	 */
	public function setSpeakableName($_speakableName) {
		$this->_speakableName = $_speakableName;
		return $this;
	}

}